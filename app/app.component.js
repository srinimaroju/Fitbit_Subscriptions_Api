(function(app) {
  app.AppComponent =
    ng.core.Component({
      selector: '.cover-heading',
      template: '<h1>Start your morning with a smile</h1>'
    })
    .Class({
      constructor: function() {}
    });
})(window.app || (window.app = {}));
