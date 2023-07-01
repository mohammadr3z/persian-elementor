(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;

var CustomCSS = function CustomCSS() {
  function customCSS() {
    var pageCSS = elementor.settings.page.model.get('raven_custom_css');

    if (pageCSS) {
      pageCSS = pageCSS.replace(/selector/g, '.elementor-page-' + elementor.config.document.id);
      elementor.settings.page.getControlsCSS().elements.$stylesheetElement.append(pageCSS);
    }
  }

  function init() {
    elementor.on('preview:loaded', customCSS);
    elementor.settings.page.model.on('change', customCSS);
  }

  return {
    init: init
  };
};

var _default = CustomCSS();

exports["default"] = _default;

},{}],2:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;

var Templates = function Templates() {
  // Find Elementor library remote template and prepend Elementorfa X badge.
  function prependBadge() {
    var templateRemote = jQuery('#tmpl-elementor-template-library-template-remote'),
        badgeHTML = "<# var ravenId = 'raven_' #>\n        <# if ( String( template_id ).substr( 0, ravenId.length ) === ravenId && typeof templatePro !== 'undefined' && templatePro ) { #>\n          <span class=\"raven-template-library-badge\">\n </span>\n        <# } else if ( String( template_id ).substr( 0, ravenId.length ) === ravenId && typeof templatePro !== 'undefined' && ! templatePro ) { #>\n          <span class=\"raven-template-library-badge raven-template-pro\">\n            <# if ( typeof ElementorfaxPremium !== 'undefined' ) { #>\n              Activate to Unlock\n            <# } else { #>\n              Upgrade to Unlock\n            <# } #>\n          </span>\n        <# } #>\n\t  ";
    var template = templateRemote.text();
    template = badgeHTML + template;
    templateRemote.text(template);
  } // Run final init when xhr/ajax action request is by getting the templates library data.


  function onRequestInit() {
    jQuery(document).ajaxComplete(function (event, request, settings) {
      if (typeof settings.data !== 'undefined' && settings.data.indexOf('get_library_data') !== -1 && settings.data.indexOf('action=elementor_ajax') !== -1) {
        setTimeout(actuallyInit, 100);
      }
    });
  }

  function actuallyInit() {
    var layout = elementor.templates.layout;

    if (typeof layout === 'undefined') {
      return;
    }

    var content = layout.modalContent; // Add Elementorfa X filter button.

    function addFilter() {
      var filter = content.$el.find('#elementor-template-library-filter-toolbar-remote');

      if (!filter.length || filter.find('.raven-template-library-filter').length) {
        return;
      }

      filter.append("\n        <div class=\"raven-template-library-filter\">\n                 </div>\n      ");
      var button = filter.find('.raven-template-library-filter-button'),
          input = content.$el.find('#elementor-template-library-filter-text'),
          query = 'Jupiter X';
      var isFiltered = false;
      button.on('click', function () {
        isFiltered = !isFiltered;
        button.toggleClass('raven-template-library-filter-active', isFiltered);
        input.trigger('input');
      });
      input.on('input', function (event) {
        if (isFiltered) {
          event.stopPropagation();
          elementor.templates.setFilter('text', "".concat(query, " - ").concat(input.val()));
        }
      });
    } // Initially apply class on initial page display.


    addFilter();
    /**
     * Listen to whenever a library menu item is clicked.
     * Such as Blocks, Pages or My Templates.
     */

    content.listenTo(content, 'show', function () {
      // Whenever modal content is changing.
      addFilter();
    });
  }

  function goProButton() {
    elementor.hooks.addFilter('elementor/editor/template-library/template/action-button', function (viewId, data) {
      var ravenId = 'raven_';

      if (String(data.template_id).substr(0, ravenId.length) === ravenId && !data.templatePro) {
        return '#tmpl-elementor-template-library-get-raven-pro-button';
      }

      return viewId;
    }, 100);
  }

  function init() {
    elementor.on('document:loaded', function () {
      // eslint-disable-next-line no-undef
      if ($e.components.get('library').hasTab('templates/persian')) {
        return;
      } // eslint-disable-next-line no-undef


      $e.components.get('library').addTab('templates/persian', {
        title: 'المنتور فارسی',
        filter: {
          source: function source() {
            elementor.channels.templates.reply('filter:source', 'remote');
            return 'persiantemplate';
          },
          type: 'block'
        }
      }, 10);
    });
    prependBadge();
    goProButton();
    onRequestInit();
  }

  return {
    init: init
  };
};

var _default = Templates();

exports["default"] = _default;

},{}],3:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;
var Checkbox = elementor.modules.controls.BaseData.extend({
  ui: function ui() {
    var ui = elementor.modules.controls.BaseData.prototype.ui.apply(this, arguments);
    ui.controlCheckbox = '.raven-control-checkbox';
    ui.mainInput = 'input[type=hidden]';
    return ui;
  },
  onReady: function onReady() {
    var self = this,
        initialValue = self.ui.mainInput.val() || '';
    var arr = initialValue.split(',');

    if (arr.length) {
      self.ui.controlCheckbox.each(function () {
        if (this.checked) {
          arr.push(this.value);
        }
      });
      arr = arr.filter(function (item, pos) {
        return arr.indexOf(item) === pos;
      });
      self.ui.mainInput.val(arr.join(','));
    }

    self.ui.controlCheckbox.on('click', function () {
      var oldVal = self.ui.mainInput.val() || '';
      var oldArr = oldVal.split(',');

      if (oldArr.length) {
        if (this.checked) {
          oldArr.push(this.value);
        } else {
          var index = oldArr.indexOf(this.value);
          oldArr.splice(index, 1);
        }

        self.ui.mainInput.val(oldArr.join(','));
        self.ui.mainInput.trigger('input');
      }
    });
  }
});
var _default = Checkbox;
exports["default"] = _default;

},{}],4:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;
var FileUploader = elementor.modules.controls.BaseMultiple.extend({
  ui: function ui() {
    var ui = elementor.modules.controls.BaseMultiple.prototype.ui.apply(this, arguments);
    ui.fileUploader = 'raven-control-file-uploader';
    ui.fileUploaderInput = '.raven-control-file-uploader-input';
    ui.fileUploaderBtn = '.raven-control-file-uploader-button';
    ui.fileUploaderValue = '.raven-control-file-uploader-value';
    ui.fileUploaderRemoveBtn = '.raven-control-file-uploader-value .fa';
    ui.fileUploaderProgress = '.raven-control-file-uploader-progress';
    ui.fileUploaderWarning = '.raven-control-file-uploader-warning';
    ui.fileUploaderSizeWarning = '.raven-control-file-uploader-warning-size';
    return ui;
  },
  events: function events() {
    return _.extend(elementor.modules.controls.BaseMultiple.prototype.events.apply(this, arguments), {
      'change @ui.fileUploaderInput': 'onFileInputChange',
      'click @ui.fileUploaderRemoveBtn': 'onFileRemove'
    });
  },
  onFileInputChange: function onFileInputChange(event) {
    var self = this;
    this.hideWarnings();

    if (event.target.files.length === 0) {
      return;
    }

    if (!this.checkFileSize(event.target.files[0])) {
      return;
    }

    var formData = new FormData();
    formData.append('action', 'raven_control_file_upload');
    formData.append('file', event.target.files[0]);
    this.showUploadProgress();
    jQuery.ajax(this.ui.fileUploaderInput.data('ajax-url'), {
      method: 'POST',
      processData: false,
      contentType: false,
      global: false,
      data: formData,
      success: function success(res) {
        if (res.success) {
          self.setValue('files', [res.data]);
          self.showFile(res.data.name);
        } else {
          self.ui.fileUploaderInput.val('');
          self.ui.fileUploaderWarning.find('ul').append("<li class=\"error\">".concat(res.data, "</li>"));
          self.ui.fileUploaderWarning.show();
          self.showUploadBtn();
        }
      },
      error: function error() {
        self.ui.fileUploaderInput.val('');
        self.ui.fileUploaderWarning.find('ul').append("<li class=\"error\">Something went wrong please try again.</li>");
        self.ui.fileUploaderWarning.show();
        self.showUploadBtn();
      }
    });
  },
  onFileRemove: function onFileRemove(event) {
    event.stopPropagation();
    this.setValue('files', []);
    this.ui.fileUploaderValue.hide();
    this.ui.fileUploaderBtn.show();
    this.ui.fileUploaderInput.val('');
  },
  hideWarnings: function hideWarnings() {
    this.ui.fileUploaderWarning.hide();
    this.ui.fileUploaderWarning.find('li').hide();
    this.ui.fileUploaderWarning.find('li.error').remove();
  },
  checkFileSize: function checkFileSize(file) {
    var uploadLimit = parseFloat(this.ui.fileUploaderInput.data('max-upload-limit'));

    if (file.size > uploadLimit) {
      this.ui.fileUploaderWarning.show();
      this.ui.fileUploaderSizeWarning.show();
      return false;
    }

    return true;
  },
  stripHash: function stripHash(filename) {
    var ext = filename.split('.').pop();
    var name = filename.replace('.' + ext, '');
    name = name.split('__').shift();
    return name + '.' + ext;
  },
  shortenFilename: function shortenFilename(filename) {
    return filename.length > 15 ? filename.substr(0, 15) + '...' : filename;
  },
  showFile: function showFile(filename) {
    this.ui.fileUploaderProgress.hide();
    this.ui.fileUploaderBtn.hide();
    filename = this.stripHash(filename);
    this.ui.fileUploaderValue.find('> span:first-child').attr('title', filename).text(this.shortenFilename(filename));
    this.ui.fileUploaderValue.css('display', 'flex');
  },
  showUploadBtn: function showUploadBtn() {
    this.ui.fileUploaderValue.hide();
    this.ui.fileUploaderProgress.hide();
    this.ui.fileUploaderBtn.show();
  },
  showUploadProgress: function showUploadProgress() {
    this.ui.fileUploaderValue.hide();
    this.ui.fileUploaderBtn.hide();
    this.ui.fileUploaderProgress.show();
  },
  onRender: function onRender() {
    _.extend(elementor.modules.controls.BaseMultiple.prototype.onRender.apply(this, arguments));

    var files = this.getControlValue('files');

    if (!files || files.length === 0) {
      return;
    }

    this.showFile(files[0].name);
  }
});
var _default = FileUploader;
exports["default"] = _default;

},{}],5:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;
var Media = elementor.modules.controls.Media.extend({
  ui: function ui() {
    var ui = elementor.modules.controls.BaseMultiple.prototype.ui.apply(this, arguments);
    ui.controlMedia = '.raven-control-media';
    ui.mediaInput = '.raven-control-media .elementor-input';
    ui.frameOpeners = '.raven-control-media-upload';
    return ui;
  },
  events: function events() {
    return _.extend(elementor.modules.controls.BaseMultiple.prototype.events.apply(this, arguments), {
      'click @ui.frameOpeners': 'openFrame'
    });
  },
  applySavedValue: function applySavedValue() {
    var url = this.getControlValue('url');
    this.ui.mediaInput.val(url);
  },
  initFrame: function initFrame() {
    this.frame = wp.media({
      button: {
        text: elementor.translate('insert_media')
      },
      states: [new wp.media.controller.Library({
        title: elementor.translate('insert_media'),
        library: wp.media.query(this.model.get('query')),
        multiple: false,
        date: false
      })]
    });
    this.frame.on('insert select', this.select.bind(this));
    this.frame.on('close', this.close.bind(this));
  },
  close: function close() {
    this.setValue({
      url: '',
      id: ''
    });
    this.render();
  }
});
var _default = Media;
exports["default"] = _default;

},{}],6:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;
var Presets = elementor.modules.controls.BaseData.extend({
  ui: function ui() {
    var ui = elementor.modules.controls.BaseMultiple.prototype.ui.apply(this, arguments);
    ui.presetItems = '.raven-element-presets';
    ui.presetItem = '.raven-element-presets-item';
    return ui;
  },
  events: function events() {
    return _.extend(elementor.modules.controls.BaseMultiple.prototype.events.apply(this, arguments), {
      'click @ui.presetItem ': 'onPresetClick'
    });
  },
  onReady: function onReady() {
    window.ravenPresets = window.ravenPresets || {};
    this.loadPresets(this.elementSettingsModel.get('widgetType'));
    elementor.channels.data.bind('raven:element:after:reset:style', this.onElementResetStyle.bind(this));
  },
  onElementResetStyle: function onElementResetStyle() {
    if (this.isRendered) {
      this.render();
    }
  },
  onPresetClick: function onPresetClick(e) {
    var $preset = $(e.currentTarget);
    $preset.siblings('.raven-element-presets-item').removeClass('active');
    $preset.addClass('active');

    var preset = _.find(this.getPresets(), {
      id: $preset.data('preset-id')
    });

    this.applyPreset(this.elementDefaultSettings(), preset);
    this.selectPreset(preset.id);
  },
  applyPreset: function applyPreset() {
    var settings = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var preset = arguments.length > 1 ? arguments[1] : undefined;

    for (var setting in preset.widget.settings) {
      if (this.model.get('name') === setting) {
        continue;
      }

      var control = this.elementSettingsModel.controls[setting];

      if (typeof control === 'undefined') {
        continue;
      }

      if (control.is_repeater) {
        this.elementSettingsModel.get(setting).reset();
        settings[setting] = new window.Backbone.Collection(preset.widget.settings[setting], {
          model: _.partial(this.createRepeaterItemModel, _, _, this)
        });
        continue;
      }

      settings[setting] = preset.widget.settings[setting];
    }

    this.elementSettingsModel.set(settings);
  },
  createRepeaterItemModel: function createRepeaterItemModel(attrs, options, controlView) {
    options = options || {};
    options.controls = controlView.elementSettingsModel.get('fields');

    if (!attrs._id) {
      attrs._id = elementor.helpers.getUniqueID();
    }

    return new window.elementorModules.editor.elements.models.BaseSettings(attrs, options);
  },
  elementDefaultSettings: function elementDefaultSettings() {
    var self = this,
        controls = self.elementSettingsModel.controls,
        settings = {};
    jQuery.each(controls, function (controlName, control) {
      if (controlName === 'raven_presets') {
        return;
      }

      settings[controlName] = control["default"];
    });
    return settings;
  },
  loadPresets: function loadPresets(widget) {
    var _this = this;

    if (this.isPresetDataLoaded()) {
      if (this.getPresets().length === 0) {
        return;
      }

      this.insertPresets();

      if (this.ui.presetItem.length === 0) {
        this.render();
      }

      return;
    }

    this.ui.presetItems.addClass('loading');
    wp.ajax.post('raven_element_presets', {
      raven_element: widget
    }).done(function (data) {
      _this.ui.presetItems.removeClass('loading');

      _this.setPresets(data);

      _this.insertPresets();

      _this.render();
    }).fail(function () {
      _this.ui.presetItems.removeClass('loading');

      _this.setPresets([]);
    });
  },
  insertPresets: function insertPresets() {
    var value = this.getControlValue();
    this.setValue({
      selectedId: value ? value.selectedId : null,
      presets: this.getPresets()
    });
  },
  selectPreset: function selectPreset(id) {
    var value = this.getControlValue();
    value.selectedId = id;
    this.setValue(value);
  },
  getPresets: function getPresets() {
    if (!window.ravenPresets) {
      return [];
    }

    return window.ravenPresets[this.elementSettingsModel.get('widgetType')] || [];
  },
  setPresets: function setPresets(presets) {
    window.ravenPresets[this.elementSettingsModel.get('widgetType')] = presets;
  },
  isPresetDataLoaded: function isPresetDataLoaded() {
    if (window.ravenPresets[this.elementSettingsModel.get('widgetType')]) {
      return true;
    }

    return false;
  },
  onBeforeDestroy: function onBeforeDestroy() {
    elementor.channels.data.unbind('raven:element:after:reset:style');
  }
});
var _default = Presets;
exports["default"] = _default;

},{}],7:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;
var Query = elementor.modules.controls.Select2.extend({
  cache: null,
  isTitlesReceived: false,
  getSelect2Placeholder: function getSelect2Placeholder() {
    var text = '';

    if (this.model.get('select2options')) {
      text = this.model.get('select2options').placeholder;
    }

    return {
      id: '',
      text: text
    };
  },
  getSelect2DefaultOptions: function getSelect2DefaultOptions() {
    var self = this;
    return jQuery.extend(elementor.modules.controls.Select2.prototype.getSelect2DefaultOptions.apply(this, arguments), {
      ajax: {
        transport: _.debounce(function (params, success, failure) {
          var action = 'raven_control_query_autocomplete',
              query = _.extend({}, self.model.get('query') || {}),
              settings = self.container.model.get('settings');

          var ids = self.getControlValue() || [];

          if (!_.isArray(ids)) {
            ids = [ids];
          }

          var source = query.source,
              controlQuery = query.control_query;
          delete query.source;
          delete query.control_query;

          for (var key in controlQuery) {
            query[key] = settings.get(controlQuery[key]);
          }

          query.s = params.data.q;

          if (!_.isEmpty(ids)) {
            query.exclude = ids;
          }

          var data = {
            source: source,
            query: query
          };
          window.elementorCommon.ajax.addRequest(action, {
            data: data,
            success: success,
            error: failure
          });
        }, 500),
        cache: true
      },
      escapeMarkup: function escapeMarkup(markup) {
        return markup;
      },
      minimumInputLength: 1
    });
  },
  getValueTitles: function getValueTitles() {
    var self = this;
    var ids = self.getControlValue() || [];

    if (!ids || _.isArray(ids) && !ids.length) {
      return;
    } else if (!_.isArray(ids)) {
      ids = [ids];
    }

    var settings = self.container.model.get('settings'),
        query = _.extend({}, self.model.get('query') || {}),
        action = 'raven_control_query_autocomplete';

    var source = query.source,
        controlQuery = query.control_query;
    delete query.source;
    delete query.control_query;

    for (var key in controlQuery) {
      query[key] = settings.get(controlQuery[key]);
    }

    query.include = ids;
    var data = {
      source: source,
      query: query
    };
    window.elementorCommon.ajax.loadObjects({
      action: action,
      ids: ids,
      data: data,
      before: function before() {
        self.addControlSpinner();
      },
      success: function success(_ref) {
        var results = _ref.results;

        if (self.isDestroyed) {
          return;
        }

        var options = [];

        if (!_.isEmpty(results)) {
          results.forEach(function (item) {
            options[item.id] = item.text;
          });
        }

        self.isTitlesReceived = true;
        self.model.set('options', options);
        self.render();
      }
    });
  },
  addControlSpinner: function addControlSpinner() {
    this.ui.select.prop('disabled', true);
    this.$el.find('.elementor-control-title').after('<span class="elementor-control-spinner">&nbsp;<i class="eicon-spinner eicon-animation-spin"></i>&nbsp;</span>');
  },
  onReady: function onReady() {
    setTimeout(elementor.modules.controls.Select2.prototype.onReady.apply(this, arguments));

    if (!this.isTitlesReceived) {
      this.getValueTitles();
    }
  }
});
var _default = Query;
exports["default"] = _default;

},{}],8:[function(require,module,exports){
"use strict";

(function ($, window) {
  var RavenEditor = function RavenEditor() {
    var self = this;

    function initComponents() {
      var components = {
        templates: require('./components/templates')["default"],
        customCSS: require('./components/custom-css')["default"]
      };

      for (var component in components) {
        components[component].init();
      }
    }

    function initControls() {
      self.controls = {
        media: require('./controls/media')["default"],
        checkbox: require('./controls/checkbox')["default"],
        file_uploader: require('./controls/file-uploader')["default"],
        presets: require('./controls/presets')["default"],
        query: require('./controls/query')["default"]
      };

      for (var control in self.controls) {
        elementor.addControlView("raven_".concat(control), self.controls[control]);
      }
    }

    function initWidgets() {
      var widgets = {
        'raven-form': require('./widgets/form')["default"],
        'raven-categories': require('./widgets/categories')["default"],
        'raven-posts': require('./widgets/posts')["default"],
        'raven-post-carousel': require('./widgets/posts')["default"]
      };

      for (var widget in widgets) {
        elementor.hooks.addAction("panel/open_editor/widget/".concat(widget), widgets[widget]);
      }
    }

    function initUtils() {
      self.utils = {
        Module: require('./utils/module')["default"],
        Form: require('./utils/form')["default"]
      };
    }

    function onElementorReady() {
      initComponents();
      initControls();
    }

    function onFrontendInit() {
      initWidgets();
    }

    function onPreviewLoaded() {
      initUtils();
      setWidgetsDarkIcon();
    }

    function onElementResetStyle(model) {
      if (model.get('elType') !== 'widget') {
        return;
      }

      resetElementPresets(model);
      elementor.channels.data.trigger('raven:element:after:reset:style', model);
    }

    function setWidgetsDarkIcon(value) {
      if (typeof elementor.settings.editorPreferences !== 'undefined') {
        $('#elementor-editor-wrapper').removeClass('raven-icon-theme-dark raven-icon-theme-light raven-icon-theme-auto');
        var uiTheme = typeof value !== 'undefined' ? value.attributes.ui_theme : elementor.settings.editorPreferences.model.get('ui_theme');
        $('#elementor-editor-wrapper').addClass('raven-icon-theme-' + uiTheme);
      }
    }

    function resetElementPresets(model) {
      var controls = model.get('settings').controls;

      if (!controls.raven_presets) {
        return;
      }

      model.setSetting('raven_presets', null);
    }

    function onElementorInit() {
      onElementorReady();
      elementor.on('frontend:init', onFrontendInit);
      elementor.on('preview:loaded', onPreviewLoaded);
      elementor.channels.data.bind('element:after:reset:style', onElementResetStyle);

      if (typeof elementor.settings.editorPreferences !== 'undefined') {
        elementor.settings.editorPreferences.model.on('change', setWidgetsDarkIcon);
      }
    }

    $(window).on('elementor:init', onElementorInit);
  };

  window.ravenEditor = new RavenEditor();
})(jQuery, window);

},{"./components/custom-css":1,"./components/templates":2,"./controls/checkbox":3,"./controls/file-uploader":4,"./controls/media":5,"./controls/presets":6,"./controls/query":7,"./utils/form":9,"./utils/module":10,"./widgets/categories":11,"./widgets/form":12,"./widgets/posts":17}],9:[function(require,module,exports){
"use strict";

var _interopRequireDefault = require("@babel/runtime/helpers/interopRequireDefault");

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;

var _module = _interopRequireDefault(require("./module"));

var Form = _module["default"].extend({
  // TODO: Translation ready.
  selectOptions: {
    "default": {
      '': 'Select one'
    },
    fetching: {
      fetching: 'Fetching...'
    },
    noList: {
      no_list: 'No list found'
    }
  },
  action: null,
  onInit: function onInit() {
    var _this = this;

    elementor.channels.editor.on('section:activated', this.onSectionActivated);

    if (this.onElementChange) {
      elementor.channels.editor.on('change', function (controlView, elementView) {
        _this.onElementChange(controlView.model.get('name'), controlView, elementView);
      });
    }
  },
  updateList: function updateList(params) {
    var self = this; // Set fetching option.

    self.setOptions(this.selectOptions.fetching);
    self.setSelectedOption(); // Send AJAX request to fetch list.

    wp.ajax.send('raven_form_editor', {
      data: _.extend({}, {
        params: params
      }, {
        service: self.action,
        request: 'get_list'
      }),
      success: self.doSuccess
    });
  },
  updateFieldMapping: function updateFieldMapping() {
    var self = this;

    _.each(self.fields, function (field, fieldKey) {
      var control = self.getControl(fieldKey);
      var controlView = self.getControlView(fieldKey);
      var options = {};
      var fieldItems = self.getRepeaterItemsByLabel('fields', field.filter);

      _.extend(options, self.selectOptions["default"], fieldItems);

      self.setOptions(options, control, controlView);
    });
  },
  getListControl: function getListControl() {
    return this.getControl("".concat(this.action, "_list"));
  },
  getListControlView: function getListControlView() {
    return this.getControlView("".concat(this.action, "_list"));
  },
  setOptions: function setOptions(options) {
    var control = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
    var controlView = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;

    if (control === null) {
      control = this.getListControl();
      controlView = this.getListControlView();
    }

    control.set('options', options);
    controlView.render();
  },
  setSelectedOption: function setSelectedOption() {
    var index = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
    var controlView = this.getListControlView();
    controlView.$el.find('select').prop('selectedIndex', index);
  },
  getRepeaterItemsByLabel: function getRepeaterItemsByLabel(propertyName, filter) {
    var items = {};
    var fieldItems = this.getElementSettings(this.model, propertyName);

    _.filter(fieldItems, function (item) {
      if (filter && item.type !== filter) {
        return;
      }

      items[item._id] = item.type;

      if (item.placeholder) {
        items[item._id] = item.placeholder;
      }

      if (item.label) {
        items[item._id] = item.label;
      }
    });

    return items;
  }
});

var _default = Form;
exports["default"] = _default;

},{"./module":10,"@babel/runtime/helpers/interopRequireDefault":18}],10:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;
var Module = elementorModules.editor.utils.Module.extend({
  panel: null,
  getControl: function getControl(propertyName) {
    if (!this.panel) {
      return;
    }

    var control = this.panel.getCurrentPageView().collection.findWhere({
      name: propertyName
    });
    return control;
  },
  getControlView: function getControlView(propertyName) {
    if (!this.panel) {
      return;
    }

    var control = this.getControl(propertyName);
    var view = this.panel.getCurrentPageView().children.findByModelCid(control.cid);
    return view;
  },
  getControlValue: function getControlValue(id) {
    return this.getControlView(id).getControlValue();
  },
  addControlSpinner: function addControlSpinner(name) {
    var $el = this.getControlView(name).$el,
        $input = $el.find(':input');

    if ($input.attr('disabled') || $el.find('.elementor-control-spinner').length > 0) {
      return;
    }

    $input.attr('disabled', true);
    $el.find('.elementor-control-title').after('<span style="display:inline-flex" class="elementor-control-spinner"><span class="fa fa-spinner fa-spin"></span>&nbsp;</span>');
  },
  removeControlSpinner: function removeControlSpinner(name) {
    var $el = this.getControlView(name).$el;
    $el.find(':input').attr('disabled', false);
    $el.find('.elementor-control-spinner').remove();
  },
  getElementSettings: function getElementSettings(model, name) {
    if (!model) {
      return null;
    }

    var value = model.get('settings').get(name);
    return value instanceof window.Backbone.Collection ? value.toJSON() : value;
  }
});
var _default = Module;
exports["default"] = _default;

},{}],11:[function(require,module,exports){
"use strict";

var _interopRequireDefault = require("@babel/runtime/helpers/interopRequireDefault");

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = _default;

var _module = _interopRequireDefault(require("../utils/module"));

function _default(panel, model, view) {
  var Categories = _module["default"].extend({
    panel: panel,
    onInit: function onInit() {
      var self = this;
      self.doAjax();
      elementor.channels.editor.on('change', function (controlView) {
        self.onElementChange(controlView.model.get('name'));
      });
    },
    onElementChange: function onElementChange(propertyName) {
      if (propertyName !== 'source') {
        return;
      }

      var specificCategoriesControl = this.getControlView('specific_categories');
      specificCategoriesControl.setValue('');
      specificCategoriesControl.render();
      this.doAjax();
    },
    doAjax: function doAjax() {
      var self = this;
      wp.ajax.send('raven_categories_editor', {
        data: {
          post_type: self.getElementSettings(model, 'source')
        },
        success: self.onSuccess
      });
    },
    onSuccess: function onSuccess(response) {
      var _this = this;

      var options = {};
      var controlIds = ['specific_categories', 'exclude'];

      _.each(response, function (term) {
        options[term.term_id] = term.name;
      });

      _.each(controlIds, function (controlId) {
        var control = _this.getControl(controlId);

        var controlView = _this.getControlView(controlId);

        control.set('options', options);

        if (!controlView) {
          return;
        }

        controlView.render();
      });
    }
  });

  new Categories({
    $element: view.$el
  });
}

},{"../utils/module":10,"@babel/runtime/helpers/interopRequireDefault":18}],12:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = _default;

function _default(panel, model, view) {
  var formActions = {
    mailchimp: require('./forms/mailchimp')["default"],
    activecampaign: require('./forms/activecampaign')["default"],
    hubspot: require('./forms/hubspot')["default"],
    email: require('./forms/email')["default"]
  };

  for (var action in formActions) {
    formActions[action](panel, model, view);
  }
}

},{"./forms/activecampaign":13,"./forms/email":14,"./forms/hubspot":15,"./forms/mailchimp":16}],13:[function(require,module,exports){
"use strict";

var _interopRequireDefault = require("@babel/runtime/helpers/interopRequireDefault");

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = _default;

var _form = _interopRequireDefault(require("../../utils/form"));

function _default(panel, model, view) {
  var ActiveCampaign = _form["default"].extend({
    panel: panel,
    model: model,
    action: 'activecampaign',
    remoteFields: [],
    onSectionActivated: function onSectionActivated(activeSection, section) {
      var _this = this;

      if (activeSection !== "section_".concat(this.action)) {
        return;
      }

      if (section.model.id !== model.get('id')) {
        return;
      }

      this.addControlSpinner('activecampaign_fields_mapping');
      this.updateList({
        activecampaign_api_key_source: this.getControlValue('activecampaign_api_key_source') || 'default',
        activecampaign_api_key: this.getControlValue('activecampaign_api_key'),
        activecampaign_api_url: this.getControlValue('activecampaign_api_url')
      });
      this.getControlView('activecampaign_fields_mapping').on('add:child', function () {
        _this.updateFieldMapping();
      });
    },
    updateFieldMapping: function updateFieldMapping() {
      var _this2 = this;

      var fieldsMapControlView = this.getControlView('activecampaign_fields_mapping');
      fieldsMapControlView.children.each(function (repeaterRow) {
        repeaterRow.children.each(function (repeaterRowField) {
          var fieldName = repeaterRowField.model.get('name');
          var fieldModel = repeaterRowField.model;

          if (fieldName === 'activecampaign_remote_field') {
            fieldModel.set('options', _this2.getRemoteFields());
          } else if (fieldName === 'activecampaign_local_field') {
            fieldModel.set('options', _this2.getFormFields());
          }

          repeaterRowField.render();
        });
      });
      this.removeControlSpinner('activecampaign_fields_mapping');
    },
    clearFieldMapping: function clearFieldMapping() {
      var fieldsMapControlView = this.getControlView('activecampaign_fields_mapping');
      fieldsMapControlView.collection.each(function (modelItem) {
        if (modelItem) {
          modelItem.destroy();
        }
      });
      fieldsMapControlView.render();
    },
    doSuccess: function doSuccess(response) {
      var self = this;
      var options = {};
      var lists = {};
      var activecampaignList = this.getElementSettings(this.model, "".concat(self.action, "_list"));

      if (response.success[0].lists.length === 0) {
        self.setOptions(this.selectOptions.noList);
        self.setSelectedOption();
        return;
      }

      _.each(response.success[0].lists, function (list) {
        lists[list.id] = list.name;
      });

      _.extend(options, {
        0: 'select one'
      }, lists);

      self.setOptions(options);

      if (!activecampaignList.length) {
        self.setSelectedOption();
      }

      this.remoteFields = response.success[0].fields;
      this.updateFieldMapping(this.remoteFields);
    },
    onElementChange: function onElementChange(setting) {
      if (setting === 'activecampaign_api_key_source' || setting === 'activecampaign_api_key' || setting === 'activecampaign_api_url') {
        this.updateList({
          activecampaign_api_key_source: this.getControlValue('activecampaign_api_key_source') || 'default',
          activecampaign_api_key: this.getControlValue('activecampaign_api_key'),
          activecampaign_api_url: this.getControlValue('activecampaign_api_url')
        });
      }

      if (setting === 'mailchimp_list') {
        this.clearFieldMapping();
        this.onListUpdate();
      }
    },
    getRemoteFields: function getRemoteFields() {
      return _.reduce(this.remoteFields, function (carry, remoteField) {
        carry[remoteField.remote_tag] = remoteField.remote_label;
        return carry;
      }, {
        '': '- None -'
      });
    },
    getFormFields: function getFormFields() {
      return _.extend({}, {
        '': '- None -'
      }, this.getRepeaterItemsByLabel('fields'));
    }
  });

  new ActiveCampaign({
    $element: view.$el
  });
}

},{"../../utils/form":9,"@babel/runtime/helpers/interopRequireDefault":18}],14:[function(require,module,exports){
"use strict";

var _interopRequireDefault = require("@babel/runtime/helpers/interopRequireDefault");

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = _default;

var _form = _interopRequireDefault(require("../../utils/form"));

function _default(panel, model, view) {
  var Email = _form["default"].extend({
    panel: panel,
    model: model,
    action: 'email',
    onSectionActivated: function onSectionActivated(activeSection, section) {
      if (activeSection !== "section_".concat(this.action)) {
        return;
      }

      if (section.model.id !== model.get('id')) {
        return;
      }

      var replyToOptionsControl = this.getControlView('email_reply_to_options');

      if (!replyToOptionsControl) {
        return;
      }

      replyToOptionsControl.model.set('options', this.getEmailFields());
      replyToOptionsControl.render();
    },
    getEmailFields: function getEmailFields() {
      return _.extend({}, {
        custom: 'Custom'
      }, this.getRepeaterItemsByLabel('fields', 'email'));
    }
  });

  new Email({
    $element: view.$el
  });
}

},{"../../utils/form":9,"@babel/runtime/helpers/interopRequireDefault":18}],15:[function(require,module,exports){
"use strict";

var _interopRequireDefault = require("@babel/runtime/helpers/interopRequireDefault");

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = _default;

var _module = _interopRequireDefault(require("./../../utils/module"));

function _default(panel, model, view) {
  var Hubspot = _module["default"].extend({
    panel: panel,
    action: 'hubspot',
    onInit: function onInit() {
      elementor.channels.editor.on('section:activated', this.onSectionActivated.bind(this));
    },
    onSectionActivated: function onSectionActivated(activeSection, section) {
      var _this = this;

      if (section.model.id !== model.get('id')) {
        return;
      }

      if (activeSection !== "section_".concat(this.action)) {
        return;
      }

      this.updateFieldMapping();
      this.getControlView('hubspot_mapping').on('add:child', function () {
        _this.updateFieldMapping();
      });
    },
    updateFieldMapping: function updateFieldMapping() {
      var _this2 = this;

      var fieldsMapControlView = this.getControlView('hubspot_mapping');
      fieldsMapControlView.children.each(function (repeaterRow) {
        repeaterRow.children.each(function (repeaterRowField) {
          var fieldName = repeaterRowField.model.get('name');
          var fieldModel = repeaterRowField.model;

          if (fieldName === 'hubspot_local_form_field') {
            fieldModel.set('options', _this2.getFormFields());
          }

          repeaterRowField.render();
        });
      });
    },
    getRepeaterItemsByLabel: function getRepeaterItemsByLabel(propertyName, filter) {
      var items = {};
      var fieldItems = this.getElementSettings(model, propertyName);

      _.filter(fieldItems, function (item) {
        if (filter && item.type !== filter) {
          return;
        }

        items[item._id] = item.label;
      });

      return items;
    },
    getFormFields: function getFormFields() {
      return _.extend({}, {
        '': '- None -'
      }, this.getRepeaterItemsByLabel('fields'));
    }
  });

  new Hubspot({
    $element: view.$el
  });
}

},{"./../../utils/module":10,"@babel/runtime/helpers/interopRequireDefault":18}],16:[function(require,module,exports){
"use strict";

var _interopRequireDefault = require("@babel/runtime/helpers/interopRequireDefault");

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = _default;

var _form = _interopRequireDefault(require("../../utils/form"));

function _default(panel, model, view) {
  var Mailchimp = _form["default"].extend({
    panel: panel,
    model: model,
    action: 'mailchimp',
    remoteFields: [],
    onSectionActivated: function onSectionActivated(activeSection, section) {
      var _this = this;

      if (activeSection !== "section_".concat(this.action)) {
        return;
      }

      if (section.model.id !== model.get('id')) {
        return;
      }

      this.addControlSpinner('mailchimp_fields_mapping');
      this.addControlSpinner('mailchimp_groups');
      this.updateList({
        mailchimp_api_key_source: this.getControlValue('mailchimp_api_key_source') || 'default',
        mailchimp_api_key: this.getControlValue('mailchimp_api_key')
      });
      this.getControlView('mailchimp_fields_mapping').on('add:child', function () {
        _this.updateFieldMapping();
      });
    },
    updateFieldMapping: function updateFieldMapping() {
      var _this2 = this;

      var fieldsMapControlView = this.getControlView('mailchimp_fields_mapping');
      fieldsMapControlView.children.each(function (repeaterRow) {
        repeaterRow.children.each(function (repeaterRowField) {
          var fieldName = repeaterRowField.model.get('name');
          var fieldModel = repeaterRowField.model;

          if (fieldName === 'mailchimp_remote_field') {
            fieldModel.set('options', _this2.getRemoteFields());
          } else if (fieldName === 'mailchimp_local_field') {
            fieldModel.set('options', _this2.getFormFields());
          }

          repeaterRowField.render();
        });
      });
    },
    clearFieldMapping: function clearFieldMapping() {
      var fieldsMapControlView = this.getControlView('mailchimp_fields_mapping');
      fieldsMapControlView.collection.each(function (modelItem) {
        if (modelItem) {
          modelItem.destroy();
        }
      });
      fieldsMapControlView.render();
    },
    doSuccess: function doSuccess(response) {
      var self = this;
      var options = {};
      var lists = {};
      var mailchimpList = this.getElementSettings(this.model, "".concat(self.action, "_list"));

      if (response.success[0].lists.length === 0) {
        self.setOptions(this.selectOptions.noList);
        self.setSelectedOption();
        return;
      }

      _.each(response.success[0].lists, function (list) {
        lists[list.id] = list.name;
      });

      _.extend(options, self.selectOptions["default"], lists);

      self.setOptions(options);

      if (!mailchimpList.length) {
        self.setSelectedOption();
      }

      this.onListUpdate();
    },
    onElementChange: function onElementChange(setting) {
      switch (setting) {
        case 'mailchimp_api_key_source':
        case 'mailchimp_api_key':
          this.unselectGroups();
          this.updateGroupOptions({});
          this.updateList({
            mailchimp_api_key_source: this.getControlValue('mailchimp_api_key_source') || 'default',
            mailchimp_api_key: this.getControlValue('mailchimp_api_key')
          });
          break;

        case 'mailchimp_list':
          this.clearFieldMapping();
          this.unselectGroups();
          this.onListUpdate();
          break;
      }
    },
    onListUpdate: function onListUpdate() {
      var _this3 = this;

      this.updateGroupOptions(this.selectOptions.fetching);
      this.addControlSpinner('mailchimp_fields_mapping');
      this.addControlSpinner('mailchimp_groups');
      wp.ajax.send('raven_form_editor', {
        data: {
          service: this.action,
          request: 'get_list_details',
          params: {
            mailchimp_api_key_source: this.getControlValue('mailchimp_api_key_source') || 'default',
            mailchimp_api_key: this.getControlValue('mailchimp_api_key'),
            mailchimp_list: this.getControlValue('mailchimp_list')
          }
        },
        success: function success(response) {
          _this3.updateGroupOptions(response.success[0].list_details.groups);

          _this3.remoteFields = response.success[0].list_details.fields;

          _this3.updateFieldMapping(_this3.remoteFields);

          _this3.removeControlSpinner('mailchimp_fields_mapping');

          _this3.removeControlSpinner('mailchimp_groups');
        }
      });
    },
    updateGroupOptions: function updateGroupOptions(groups) {
      var control = this.getControl('mailchimp_groups');
      var controlView = this.getControlView('mailchimp_groups');
      this.setOptions(groups, control, controlView);
    },
    getRemoteFields: function getRemoteFields() {
      return _.reduce(this.remoteFields, function (carry, remoteField) {
        carry[remoteField.remote_tag] = remoteField.remote_label;
        return carry;
      }, {
        '': '- None -'
      });
    },
    getFormFields: function getFormFields() {
      return _.extend({}, {
        '': '- None -'
      }, this.getRepeaterItemsByLabel('fields'));
    },
    unselectGroups: function unselectGroups() {
      var controlView = this.getControlView('mailchimp_groups');
      controlView.setValue('');
      controlView.render();
    }
  });

  new Mailchimp({
    $element: view.$el
  });
}

},{"../../utils/form":9,"@babel/runtime/helpers/interopRequireDefault":18}],17:[function(require,module,exports){
"use strict";

var _interopRequireDefault = require("@babel/runtime/helpers/interopRequireDefault");

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = _default;

var _module = _interopRequireDefault(require("../utils/module"));

function _default(panel, model, view) {
  var Posts = _module["default"].extend({
    panel: panel,
    onInit: function onInit() {
      var _this = this;

      if (this.onElementChange) {
        elementor.channels.editor.on('change', function (controlView) {
          _this.onElementChange(controlView.model.get('name'), controlView);
        });
      }
    },
    onElementChange: function onElementChange(name, controlView) {
      switch (name) {
        case 'query_post_type':
          this.onQueryPostTypeChange(controlView);
          break;
      }
    },
    onQueryPostTypeChange: function onQueryPostTypeChange(controlView) {
      controlView.container.settings.set('query_excludes_ids', []);
    }
  });

  new Posts({
    $element: view.$el
  });
}

},{"../utils/module":10,"@babel/runtime/helpers/interopRequireDefault":18}],18:[function(require,module,exports){
function _interopRequireDefault(obj) {
  return obj && obj.__esModule ? obj : {
    "default": obj
  };
}

module.exports = _interopRequireDefault;
},{}]},{},[8]);
