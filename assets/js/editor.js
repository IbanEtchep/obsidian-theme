/**
 * Obsidian Editor - Visual drag-and-drop page builder
 */
var ObsidianEditor = {
    state: {
        sections: [],
        colors: {},
        footer: {},
        dirty: false,
        editing: false
    },

    // Bootstrap modal instances
    widgetModal: null,
    catalogModal: null,
    currentEditId: null,
    currentAddAfter: null,
    sortableInstance: null,
    configKey: 'sections',
    hasZones: false,
    currentLayout: 'full',
    sortableInstances: [],

    /* ========== INIT ========== */
    init: function () {
        if (!window.obsidianConfig) return;

        this.configKey = window.obsidianConfigKey || 'sections';
        this.hasZones = !!window.obsidianHasZones;
        this.currentLayout = (window.obsidianConfig.layout) || 'full';
        this.state.sections = JSON.parse(JSON.stringify(window.obsidianConfig.sections || []));
        this.state.colors = JSON.parse(JSON.stringify(window.obsidianConfig.colors || {}));

        this.widgetModal = new bootstrap.Modal(document.getElementById('obsidianWidgetModal'));
        this.catalogModal = new bootstrap.Modal(document.getElementById('obsidianCatalogModal'));

        this.bindToggle();
        this.bindSave();
        this.bindColors();
        this.bindPresets();
        this.bindCatalog();
        this.bindModalApply();
        this.bindLayout();
        this.bindFooter();

        // Auto-enter edit mode if ?editor=true
        if (new URLSearchParams(window.location.search).get('editor') === 'true') {
            this.enterEditMode();
        }
    },

    /* ========== EDIT MODE ========== */
    enterEditMode: function () {
        this.state.editing = true;
        document.body.classList.add('obsidian-editing-mode');

        var sidebar = document.getElementById('obsidian-sidebar');
        sidebar.classList.remove('d-none');
        setTimeout(function () { sidebar.classList.add('open'); }, 10);

        var toggle = document.getElementById('obsidian-editor-toggle');
        toggle.classList.add('active');
        toggle.querySelector('i').className = 'bi bi-x-lg';

        // Inject toolbars into each widget
        var self = this;
        document.querySelectorAll('.obsidian-widget').forEach(function (w) {
            w.classList.add('obsidian-editing');
            self.injectToolbar(w);
        });

        // Init sortable
        this.initSortable();
    },

    exitEditMode: function () {
        this.state.editing = false;
        document.body.classList.remove('obsidian-editing-mode');

        var sidebar = document.getElementById('obsidian-sidebar');
        sidebar.classList.remove('open');
        setTimeout(function () { sidebar.classList.add('d-none'); }, 300);

        var toggle = document.getElementById('obsidian-editor-toggle');
        toggle.classList.remove('active');
        toggle.querySelector('i').className = 'bi bi-pencil-square';

        // Remove toolbars
        document.querySelectorAll('.obsidian-widget').forEach(function (w) {
            w.classList.remove('obsidian-editing');
            var tb = w.querySelector('.obsidian-widget-toolbar');
            if (tb) tb.remove();
        });

        if (this.sortableInstance) {
            this.sortableInstance.destroy();
            this.sortableInstance = null;
        }

        if (this.state.dirty) {
            window.location.reload();
        }
    },

    activeToolbar: null,
    toolbarHideTimeout: null,

    /* ========== TOOLBAR INJECTION ========== */
    injectToolbar: function (widgetEl) {
        var id = widgetEl.getAttribute('data-widget-id');
        var self = this;

        // Create a single shared toolbar if not exists
        if (!document.getElementById('obsidian-shared-toolbar')) {
            var toolbar = document.createElement('div');
            toolbar.id = 'obsidian-shared-toolbar';
            toolbar.className = 'obsidian-widget-toolbar';
            toolbar.innerHTML =
                '<button class="obsidian-toolbar-btn" data-action="edit" title="Modifier"><i class="bi bi-pencil-fill"></i></button>' +
                '<button class="obsidian-toolbar-btn danger" data-action="delete" title="Supprimer"><i class="bi bi-trash-fill"></i></button>';
            document.body.appendChild(toolbar);

            // Keep toolbar visible when hovering it
            toolbar.addEventListener('mouseenter', function () {
                clearTimeout(self.toolbarHideTimeout);
            });
            toolbar.addEventListener('mouseleave', function () {
                self.toolbarHideTimeout = setTimeout(function () {
                    toolbar.classList.remove('visible');
                    self.activeToolbar = null;
                }, 200);
            });
        }

        // Show toolbar on hover
        widgetEl.addEventListener('mouseenter', function () {
            clearTimeout(self.toolbarHideTimeout);
            self.showToolbarFor(widgetEl);
        });
        widgetEl.addEventListener('mouseleave', function () {
            self.toolbarHideTimeout = setTimeout(function () {
                var tb = document.getElementById('obsidian-shared-toolbar');
                if (tb) tb.classList.remove('visible');
                self.activeToolbar = null;
            }, 200);
        });
    },

    showToolbarFor: function (widgetEl) {
        var toolbar = document.getElementById('obsidian-shared-toolbar');
        if (!toolbar) return;

        var id = widgetEl.getAttribute('data-widget-id');
        var self = this;
        var rect = widgetEl.getBoundingClientRect();

        // Position: centered horizontally above the widget, below navbar
        var topPos = Math.max(rect.top + 8, 80); // at least 80px from top (below navbar)
        var leftPos = rect.left + rect.width / 2;

        toolbar.style.top = topPos + 'px';
        toolbar.style.left = leftPos + 'px';
        toolbar.style.transform = 'translateX(-50%)';
        toolbar.classList.add('visible');

        // Rebind actions for current widget
        var editBtn = toolbar.querySelector('[data-action="edit"]');
        var deleteBtn = toolbar.querySelector('[data-action="delete"]');

        var newEdit = editBtn.cloneNode(true);
        var newDelete = deleteBtn.cloneNode(true);
        editBtn.replaceWith(newEdit);
        deleteBtn.replaceWith(newDelete);

        newEdit.addEventListener('click', function (e) {
            e.stopPropagation();
            self.openWidgetEditor(id);
        });
        newDelete.addEventListener('click', function (e) {
            e.stopPropagation();
            self.deleteWidget(id);
        });

        this.activeToolbar = id;
    },

    /* ========== SORTABLE ========== */
    initSortable: function () {
        if (!window.Sortable) return;
        var self = this;

        // Destroy previous instances
        this.sortableInstances.forEach(function (s) { s.destroy(); });
        this.sortableInstances = [];

        // Find all zone containers (or the main #obsidian-sections if no zones)
        var zones = document.querySelectorAll('.obsidian-zone');
        if (!zones.length) {
            var main = document.getElementById('obsidian-sections');
            if (main) zones = [main];
        }

        zones.forEach(function (zone) {
            var zoneName = zone.getAttribute('data-zone') || 'main';

            var instance = Sortable.create(zone, {
                animation: 200,
                group: 'obsidian-widgets', // allows dragging between zones
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                draggable: '.obsidian-widget',
                filter: '.obsidian-add-zone',
                onStart: function () {
                    zone.querySelectorAll('.obsidian-add-zone').forEach(function (z) { z.style.display = 'none'; });
                    var tb = document.getElementById('obsidian-shared-toolbar');
                    if (tb) tb.classList.remove('visible');
                },
                onEnd: function (evt) {
                    // Show add zones in all zones
                    document.querySelectorAll('.obsidian-add-zone').forEach(function (z) { z.style.display = ''; });

                    // Update zone + order for all widgets in all zones
                    document.querySelectorAll('.obsidian-zone, #obsidian-sections').forEach(function (z) {
                        var zn = z.getAttribute('data-zone') || 'main';
                        z.querySelectorAll('.obsidian-widget').forEach(function (w, i) {
                            var wid = w.getAttribute('data-widget-id');
                            var section = self.findSection(wid);
                            if (section) {
                                section.order = i;
                                section.zone = zn;
                                w.setAttribute('data-zone', zn);
                            }
                        });
                    });
                    self.markDirty();
                }
            });
            self.sortableInstances.push(instance);
        });
    },

    /* ========== WIDGET EDITOR ========== */
    openWidgetEditor: function (widgetId) {
        var section = this.findSection(widgetId);
        if (!section) return;

        this.currentEditId = widgetId;
        var body = document.getElementById('obsidian-modal-body');
        body.innerHTML = '';

        var data = section.data || {};
        var fieldLabels = this.getFieldLabels(section.type);
        var widgetEl = document.querySelector('[data-widget-id="' + widgetId + '"]');

        Object.keys(data).forEach(function (key) {
            var group = document.createElement('div');
            group.className = 'obsidian-field-group';

            var label = document.createElement('label');
            label.textContent = fieldLabels[key] || key;
            label.setAttribute('for', 'field-' + key);

            var isLong = (key === 'content' || key === 'subtitle' || key.indexOf('desc') > -1);
            var input;
            if (isLong) {
                input = document.createElement('textarea');
                input.rows = 3;
            } else {
                input = document.createElement('input');
                input.type = 'text';
            }
            input.className = 'form-control';
            input.id = 'field-' + key;
            input.name = key;

            // Use stored value, or read displayed text from DOM as fallback
            var val = data[key];
            if (!val && widgetEl) {
                var domField = widgetEl.querySelector('[data-field="' + key + '"]');
                if (domField) {
                    val = key === 'content' ? domField.innerHTML.trim() : domField.textContent.trim();
                }
            }
            input.value = val || '';

            group.appendChild(label);
            group.appendChild(input);
            body.appendChild(group);
        });

        document.getElementById('obsidian-modal-title').textContent =
            this.getWidgetLabel(section.type);
        this.widgetModal.show();
    },

    /* ========== DELETE WIDGET ========== */
    deleteWidget: function (widgetId) {
        var confirmMsg = (window.obsidianI18n && window.obsidianI18n.delete_confirm) || 'Delete this widget?';
        if (!confirm(confirmMsg)) return;

        // Remove from state
        this.state.sections = this.state.sections.filter(function (s) { return s.id !== widgetId; });

        // Remove from DOM
        var el = document.querySelector('[data-widget-id="' + widgetId + '"]');
        if (el) {
            var addZone = el.nextElementSibling;
            if (addZone && addZone.classList.contains('obsidian-add-zone')) addZone.remove();
            el.remove();
        }

        this.markDirty();
    },

    /* ========== ADD WIDGET ========== */
    openCatalog: function (afterId) {
        this.currentAddAfter = afterId;
        this.catalogModal.show();
    },

    addWidget: function (type) {
        var newId = type + '-' + Date.now();
        var defaults = (window.obsidianWidgetDefaults || {})[type] || {};

        var maxOrder = 0;
        this.state.sections.forEach(function (s) {
            if (s.order > maxOrder) maxOrder = s.order;
        });

        // Determine zone from the add button's context
        var addZone = this.currentAddAfter ?
            (document.querySelector('.obsidian-add-zone[data-after="' + this.currentAddAfter + '"]') || {}) : {};
        var zone = addZone.getAttribute ? (addZone.getAttribute('data-zone') || 'main') : 'main';

        var newSection = {
            id: newId,
            type: type,
            zone: zone,
            order: maxOrder + 1,
            visible: true,
            data: JSON.parse(JSON.stringify(defaults)),
            settings: {}
        };

        // Insert after currentAddAfter
        if (this.currentAddAfter) {
            var afterIdx = -1;
            for (var i = 0; i < this.state.sections.length; i++) {
                if (this.state.sections[i].id === this.currentAddAfter) { afterIdx = i; break; }
            }
            if (afterIdx >= 0) {
                newSection.order = this.state.sections[afterIdx].order + 0.5;
                this.state.sections.splice(afterIdx + 1, 0, newSection);
            } else {
                this.state.sections.push(newSection);
            }
        } else {
            this.state.sections.push(newSection);
        }

        // Re-index orders
        this.state.sections.sort(function (a, b) { return a.order - b.order; });
        this.state.sections.forEach(function (s, i) { s.order = i; });

        this.catalogModal.hide();
        this.markDirty();

        // Reload to show new widget (server renders Blade)
        this.save(true);
    },

    /* ========== COLORS ========== */
    updateColor: function (key, value) {
        this.state.colors[key] = value;

        // Live update CSS variables
        var cssMap = {
            primary: '--obsidian-primary',
            secondary: '--obsidian-secondary',
            accent: '--obsidian-accent',
            navbar: '--obsidian-navbar-bg',
            footer: '--obsidian-footer-bg',
            hero_start: '--obsidian-hero-start',
            hero_end: '--obsidian-hero-end'
        };

        if (cssMap[key]) {
            document.documentElement.style.setProperty(cssMap[key], value);
        }

        // Also update RGB variants for primary/secondary
        if (key === 'primary' || key === 'secondary' || key === 'accent') {
            var rgb = this.hexToRgb(value);
            if (rgb) {
                document.documentElement.style.setProperty(
                    '--obsidian-' + key + '-rgb',
                    rgb.r + ', ' + rgb.g + ', ' + rgb.b
                );
            }
        }

        this.markDirty();
    },

    applyPreset: function (preset) {
        var self = this;
        Object.keys(preset).forEach(function (key) {
            self.state.colors[key] = preset[key];
            self.updateColor(key, preset[key]);

            // Update input
            var input = document.querySelector('.obsidian-color-input[data-color-key="' + key + '"]');
            if (input) input.value = preset[key];
        });
    },

    /* ========== SAVE ========== */
    save: function (reload) {
        var self = this;
        var fd = new FormData();

        // Colors
        Object.keys(this.state.colors).forEach(function (key) {
            fd.append('colors[' + key + ']', self.state.colors[key]);
        });

        // Layout (for zone pages)
        if (this.hasZones) {
            fd.append('vote_layout', this.currentLayout);
        }

        // Sections — $replace forces full replacement instead of merge
        var configKey = this.configKey;
        fd.append(configKey + '[$replace]', 'true');
        this.state.sections.forEach(function (section, i) {
            var prefix = configKey + '[' + i + ']';
            fd.append(prefix + '[id]', section.id);
            fd.append(prefix + '[type]', section.type);
            fd.append(prefix + '[zone]', section.zone || 'main');
            fd.append(prefix + '[order]', section.order);
            fd.append(prefix + '[visible]', section.visible ? '1' : '0');

            var data = section.data || {};
            Object.keys(data).forEach(function (key) {
                fd.append(prefix + '[data][' + key + ']', data[key] || '');
            });

            var settings = section.settings || {};
            Object.keys(settings).forEach(function (key) {
                fd.append(prefix + '[settings][' + key + ']', settings[key] ? '1' : '0');
            });
        });

        // Footer
        if (this.state.footer && Object.keys(this.state.footer).length) {
            fd.append('footer[description]', self.state.footer.description || '');
            fd.append('footer[links_title]', self.state.footer.links_title || '');
            fd.append('footer[social_title]', self.state.footer.social_title || '');
            fd.append('footer[legal_title]', self.state.footer.legal_title || '');
            if (self.state.footer.links) {
                self.state.footer.links.forEach(function (link, i) {
                    fd.append('footer[links][' + i + '][name]', link.name);
                    fd.append('footer[links][' + i + '][url]', link.url);
                });
            }
            if (self.state.footer.legal_links) {
                self.state.footer.legal_links.forEach(function (link, i) {
                    fd.append('footer[legal_links][' + i + '][name]', link.name);
                    fd.append('footer[legal_links][' + i + '][url]', link.url);
                });
            }
        }

        // Tell Azuriom to merge with existing config
        fd.append('append', 'true');

        var saveBtn = document.getElementById('obsidian-save');
        saveBtn.querySelector('.obsidian-save-text').classList.add('d-none');
        saveBtn.querySelector('.obsidian-save-loading').classList.remove('d-none');
        saveBtn.disabled = true;

        var submitUrl = document.getElementById('obsidian-submit-url').textContent.trim();

        axios.post(submitUrl, fd, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(function () {
            self.state.dirty = false;

            saveBtn.querySelector('.obsidian-save-loading').classList.add('d-none');
            saveBtn.querySelector('.obsidian-save-done').classList.remove('d-none');

            setTimeout(function () {
                saveBtn.querySelector('.obsidian-save-done').classList.add('d-none');
                saveBtn.querySelector('.obsidian-save-text').classList.remove('d-none');
                saveBtn.disabled = true;
            }, 2000);

            if (reload) {
                setTimeout(function () {
                    window.location.href = window.location.pathname + '?editor=true';
                }, 500);
            }
        }).catch(function (err) {
            console.error('Save failed:', err);
            if (err.response) {
                console.error('Response status:', err.response.status);
                console.error('Response data:', err.response.data);
            }
            saveBtn.querySelector('.obsidian-save-loading').classList.add('d-none');
            saveBtn.querySelector('.obsidian-save-text').classList.remove('d-none');
            saveBtn.disabled = false;
            var msg = 'Erreur lors de la sauvegarde.';
            if (err.response && err.response.data && err.response.data.message) {
                msg += '\n' + err.response.data.message;
            }
            alert(msg);
        });
    },

    /* ========== BINDINGS ========== */
    bindToggle: function () {
        var self = this;
        document.getElementById('obsidian-editor-toggle').addEventListener('click', function () {
            if (self.state.editing) {
                self.exitEditMode();
            } else {
                self.enterEditMode();
            }
        });
    },

    bindSave: function () {
        var self = this;
        document.getElementById('obsidian-save').addEventListener('click', function () {
            self.save(false);
        });
    },

    bindColors: function () {
        var self = this;
        document.querySelectorAll('.obsidian-color-input').forEach(function (input) {
            input.addEventListener('input', function () {
                self.updateColor(input.getAttribute('data-color-key'), input.value);
            });
        });
    },

    bindPresets: function () {
        var self = this;
        document.querySelectorAll('.obsidian-preset-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var preset = JSON.parse(btn.getAttribute('data-preset'));
                self.applyPreset(preset);
            });
        });
    },

    bindCatalog: function () {
        var self = this;

        // "+" buttons
        document.addEventListener('click', function (e) {
            var addBtn = e.target.closest('.obsidian-add-btn');
            if (!addBtn) return;
            var zone = addBtn.closest('.obsidian-add-zone');
            var afterId = zone ? zone.getAttribute('data-after') : '';
            self.openCatalog(afterId);
        });

        // Catalog items
        document.querySelectorAll('.obsidian-catalog-item').forEach(function (item) {
            item.addEventListener('click', function () {
                self.addWidget(item.getAttribute('data-widget-type'));
            });
        });
    },

    bindLayout: function () {
        var self = this;
        var section = document.getElementById('obsidian-layout-section');
        if (!this.hasZones || !section) return;

        section.classList.remove('d-none');

        // Highlight current
        document.querySelectorAll('.obsidian-layout-opt').forEach(function (btn) {
            if (btn.getAttribute('data-layout') === self.currentLayout) {
                btn.classList.add('active');
            }
            btn.addEventListener('click', function () {
                document.querySelectorAll('.obsidian-layout-opt').forEach(function (b) { b.classList.remove('active'); });
                btn.classList.add('active');
                self.currentLayout = btn.getAttribute('data-layout');
                self.markDirty();
                // Save and reload to apply layout change
                self.save(true);
            });
        });
    },

    bindFooter: function () {
        var self = this;
        var footer = document.getElementById('obsidian-footer');
        var modal = document.getElementById('obsidianFooterModal');
        var applyBtn = document.getElementById('obsidian-footer-apply');
        if (!footer || !modal || !applyBtn) return;

        var footerModal = new bootstrap.Modal(modal);

        // Inject toolbar into footer
        var toolbar = document.createElement('div');
        toolbar.id = 'obsidian-footer-toolbar';
        toolbar.innerHTML = '<button class="obsidian-toolbar-btn" title="Modifier le footer"><i class="bi bi-pencil-fill"></i></button>';
        footer.appendChild(toolbar);

        toolbar.querySelector('button').addEventListener('click', function () {
            footerModal.show();
        });

        applyBtn.addEventListener('click', function () {
            var desc = document.getElementById('obsidian-footer-description').value;
            var linksTitle = document.getElementById('obsidian-footer-links-title').value;
            var socialTitle = document.getElementById('obsidian-footer-social-title').value;

            // Collect custom links
            var links = [];
            document.querySelectorAll('.obsidian-footer-link-row').forEach(function (row) {
                var name = row.querySelector('[data-link-name]').value.trim();
                var url = row.querySelector('[data-link-url]').value.trim();
                if (name && url) links.push({ name: name, url: url });
            });

            // Collect legal links
            var legalTitle = document.getElementById('obsidian-footer-legal-title').value;
            var legalLinks = [];
            document.querySelectorAll('.obsidian-footer-legal-row').forEach(function (row) {
                var name = row.querySelector('[data-legal-name]').value.trim();
                var url = row.querySelector('[data-legal-url]').value.trim();
                if (name && url) legalLinks.push({ name: name, url: url });
            });

            self.state.footer = {
                description: desc,
                links_title: linksTitle,
                social_title: socialTitle,
                links: links,
                legal_title: legalTitle,
                legal_links: legalLinks
            };

            // Live update DOM text
            var descEl = document.querySelector('[data-footer-field="description"]');
            var linksEl = document.querySelector('[data-footer-field="links_title"]');
            var socialEl = document.querySelector('[data-footer-field="social_title"]');
            if (descEl && desc) descEl.textContent = desc;
            if (linksEl && linksTitle) linksEl.textContent = linksTitle;
            if (socialEl && socialTitle) socialEl.textContent = socialTitle;

            // Live update links list
            var linksList = document.getElementById('obsidian-footer-links-display');
            if (linksList && links.length > 0) {
                linksList.innerHTML = '';
                links.forEach(function (l) {
                    var li = document.createElement('li');
                    li.innerHTML = '<a href="' + l.url + '">' + l.name + '</a>';
                    linksList.appendChild(li);
                });
            }

            // Live update legal
            var legalEl = document.querySelector('[data-footer-field="legal_title"]');
            if (legalEl && legalTitle) legalEl.textContent = legalTitle;

            var legalList = document.getElementById('obsidian-footer-legal-display');
            if (legalList && legalLinks.length > 0) {
                legalList.innerHTML = '';
                legalLinks.forEach(function (l) {
                    var li = document.createElement('li');
                    li.innerHTML = '<a href="' + l.url + '">' + l.name + '</a>';
                    legalList.appendChild(li);
                });
            }

            footerModal.hide();
            self.markDirty();
        });
    },

    bindModalApply: function () {
        var self = this;
        document.getElementById('obsidian-modal-apply').addEventListener('click', function () {
            if (!self.currentEditId) return;

            var section = self.findSection(self.currentEditId);
            if (!section) return;

            // Read all fields from modal
            var widgetEl = document.querySelector('[data-widget-id="' + self.currentEditId + '"]');
            var body = document.getElementById('obsidian-modal-body');
            body.querySelectorAll('input, textarea').forEach(function (input) {
                var key = input.name;
                if (key && section.data.hasOwnProperty(key)) {
                    section.data[key] = input.value;

                    // Update anchor id on the widget element
                    if (key === 'anchor' && widgetEl) {
                        if (input.value) {
                            widgetEl.id = input.value;
                        } else {
                            widgetEl.removeAttribute('id');
                        }
                    }

                    // Update DOM text
                    if (widgetEl) {
                        var field = widgetEl.querySelector('[data-field="' + key + '"]');
                        if (field) {
                            if (key === 'content') {
                                field.innerHTML = input.value;
                            } else {
                                field.textContent = input.value;
                            }
                        }
                    }
                }
            });

            self.widgetModal.hide();
            self.markDirty();
        });
    },

    /* ========== HELPERS ========== */
    findSection: function (id) {
        for (var i = 0; i < this.state.sections.length; i++) {
            if (this.state.sections[i].id === id) return this.state.sections[i];
        }
        return null;
    },

    markDirty: function () {
        this.state.dirty = true;
        var btn = document.getElementById('obsidian-save');
        if (btn) btn.disabled = false;
    },

    hexToRgb: function (hex) {
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    },

    getFieldLabels: function (type) {
        var f = (window.obsidianI18n && window.obsidianI18n.fields) || {};
        var featLabel = function (baseKey, n) {
            return (f[baseKey] || baseKey).replace(':n', n);
        };
        var labels = {
            hero: { anchor: f.anchor, logo_url: f.logo_url, logo_size: f.logo_size, title: f.title, subtitle: f.subtitle, button_text: f.button_text, button_url: f.button_url, server_ip: f.server_ip },
            features: {
                anchor: f.anchor, title: f.section_title,
                feature_1_icon: featLabel('feature_icon', 1), feature_1_title: featLabel('feature_title', 1), feature_1_desc: featLabel('feature_desc', 1),
                feature_2_icon: featLabel('feature_icon', 2), feature_2_title: featLabel('feature_title', 2), feature_2_desc: featLabel('feature_desc', 2),
                feature_3_icon: featLabel('feature_icon', 3), feature_3_title: featLabel('feature_title', 3), feature_3_desc: featLabel('feature_desc', 3)
            },
            servers: { anchor: f.anchor, title: f.section_title },
            news: { anchor: f.anchor, title: f.section_title },
            discord: { anchor: f.anchor, title: f.title, subtitle: f.subtitle, discord_id: f.discord_id },
            cta: { anchor: f.anchor, title: f.title, subtitle: f.subtitle, button_text: f.button_text, button_url: f.button_url },
            join: { anchor: f.anchor, title: f.title, step_1: featLabel('step', 1), step_2: featLabel('step', 2), step_3: featLabel('step', 3), server_ip: f.server_ip },
            youtube: { anchor: f.anchor, title: f.title, subtitle: f.subtitle, video_id: f.video_id },
            custom: { title: f.title, content: f.content },
            'vote/panel': { title: f.title },
            'vote/leaderboard': { title: f.title },
            'vote/goal': { title: f.title },
            'vote/rewards': { title: f.title },
            'vote/steps-h': { title: f.title },
            'vote/steps-v': { title: f.title }
        };
        return labels[type] || {};
    },

    getWidgetLabel: function (type) {
        var w = (window.obsidianI18n && window.obsidianI18n.widgets) || {};
        var map = {
            'hero': w.hero, 'features': w.features, 'servers': w.servers,
            'news': w.news, 'discord': w.discord, 'cta': w.cta,
            'join': w.join, 'youtube': w.youtube, 'custom': w.custom,
            'vote/panel': w.vote_panel, 'vote/leaderboard': w.vote_leaderboard,
            'vote/goal': w.vote_goal, 'vote/rewards': w.vote_rewards,
            'vote/steps-h': w.vote_steps_h, 'vote/steps-v': w.vote_steps_v
        };
        return map[type] || type;
    }
};

document.addEventListener('DOMContentLoaded', function () {
    ObsidianEditor.init();
});
