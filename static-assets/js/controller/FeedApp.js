var app = app || {};

define([
  'underscore',
  'backbone',
  'bootstrap',
  'modernizr',
  'imageScale',
  'views/SocialFeedView'
], function(_, Backbone, bootstrap, modernizr, imageScale, SocialFeedView){
  app.dispatcher = _.clone(Backbone.Events);

  _.templateSettings = {
      evaluate:    /\{\{(.+?)\}\}/g,
      interpolate: /\{\{=(.+?)\}\}/g,
      escape:      /\{\{-(.+?)\}\}/g
  };

  var initialize = function() {
    $(window).resize(function() {
      handleResize();
    });
    handleResize();

    setupUI();

    $('img.scale').imageScale({'rescaleOnResize': true, 'fadeInDuration': 500});

    app.dispatcher.on("SocialFeatureView:feedready", onSocialFeedReady);

    var socialFeedView = new SocialFeedView({ el: '#journal-view' });
    socialFeedView.loadFeed();

    function onSocialFeedReady() {
      socialFeedView.render();
    }
  };

  return { 
    initialize: initialize
  };
});

