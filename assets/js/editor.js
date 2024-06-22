"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;

var Templates = function Templates() {
  function prependBadge() {
    var templateRemote = jQuery('#tmpl-elementor-template-library-template-remote'),
        badgeHTML = "";
    var template = templateRemote.text();
    template = badgeHTML + template;
    templateRemote.text(template);
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
      if ($e.components.get('library').hasTab('templates/persian')) {
        return;
      }

      $e.components.get('library').addTab('templates/persian', {
        title: '<a href="https://temply.ir/" target="_blank" style="color: #0c0d0e; padding:17px 25px">قالب های فارسی</a>',
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
