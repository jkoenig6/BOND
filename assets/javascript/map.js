/*! js-url - v2.5.3 - 2018-04-05 */ ! function () {
  var a = function () {
    function a() {}

    function b(a) {
      return decodeURIComponent(a.replace(/\+/g, " "))
    }

    function c(a, b) {
      var c = a.charAt(0),
        d = b.split(c);
      return c === a ? d : (a = parseInt(a.substring(1), 10), d[a < 0 ? d.length + a : a - 1])
    }

    function d(a, c) {
      for (var d = a.charAt(0), e = c.split("&"), f = [], g = {}, h = [], i = a.substring(1), j = 0, k = e.length; j < k; j++)
        if (f = e[j].match(/(.*?)=(.*)/), f || (f = [e[j], e[j], ""]), "" !== f[1].replace(/\s/g, "")) {
          if (f[2] = b(f[2] || ""), i === f[1]) return f[2];
          h = f[1].match(/(.*)\[([0-9]+)\]/), h ? (g[h[1]] = g[h[1]] || [], g[h[1]][h[2]] = f[2]) : g[f[1]] = f[2]
        } return d === a ? g : g[i]
    }
    return function (b, e) {
      var f, g = {};
      if ("tld?" === b) return a();
      if (e = e || window.location.toString(), !b) return e;
      if (b = b.toString(), f = e.match(/^mailto:([^\/].+)/)) g.protocol = "mailto", g.email = f[1];
      else {
        if ((f = e.match(/(.*?)\/#\!(.*)/)) && (e = f[1] + f[2]), (f = e.match(/(.*?)#(.*)/)) && (g.hash = f[2], e = f[1]), g.hash && b.match(/^#/)) return d(b, g.hash);
        if ((f = e.match(/(.*?)\?(.*)/)) && (g.query = f[2], e = f[1]), g.query && b.match(/^\?/)) return d(b, g.query);
        if ((f = e.match(/(.*?)\:?\/\/(.*)/)) && (g.protocol = f[1].toLowerCase(), e = f[2]), (f = e.match(/(.*?)(\/.*)/)) && (g.path = f[2], e = f[1]), g.path = (g.path || "").replace(/^([^\/])/, "/$1"), b.match(/^[\-0-9]+$/) && (b = b.replace(/^([^\/])/, "/$1")), b.match(/^\//)) return c(b, g.path.substring(1));
        if (f = c("/-1", g.path.substring(1)), f && (f = f.match(/(.*?)\.([^.]+)$/)) && (g.file = f[0], g.filename = f[1], g.fileext = f[2]), (f = e.match(/(.*)\:([0-9]+)$/)) && (g.port = f[2], e = f[1]), (f = e.match(/(.*?)@(.*)/)) && (g.auth = f[1], e = f[2]), g.auth && (f = g.auth.match(/(.*)\:(.*)/), g.user = f ? f[1] : g.auth, g.pass = f ? f[2] : void 0), g.hostname = e.toLowerCase(), "." === b.charAt(0)) return c(b, g.hostname);
        a() && (f = g.hostname.match(a()), f && (g.tld = f[3], g.domain = f[2] ? f[2] + "." + f[3] : void 0, g.sub = f[1] || void 0)), g.port = g.port || ("https" === g.protocol ? "443" : "80"), g.protocol = g.protocol || ("443" === g.port ? "https" : "http")
      }
      return b in g ? g[b] : "{}" === b ? g : void 0
    }
  }();
  "function" == typeof window.define && window.define.amd ? window.define("js-url", [], function () {
    return a
  }) : ("undefined" != typeof window.jQuery && window.jQuery.extend({
    url: function (a, b) {
      return window.url(a, b)
    }
  }), window.url = a)
}();


jQuery(document).ready(function ($) {


  //Functions to show/hide regions and schools

  var regionID;
  var schoolID;

  function regionShow() {
    $('#overview-map-container').addClass('hide');
    $('.zoom-div.region-map').removeClass('hide');
    $('#region-container-' + regionID).removeClass('hide');
    $('#map-svg-region-' + regionID).removeClass('hide');
    $('#school-list-' + regionID).removeClass('hide');
    $('#region-key-' + regionID).removeClass('hide');
    $('.map-marker-container').removeClass('hide');
    history.replaceState(null, null, '?region=' + regionID);
  }


  function regionHide() {
    $('#overview-map-container').removeClass('hide');
    $('.zoom-div.region-map').addClass('hide');
    $('.region-container').addClass('hide');
    $('.map-svg-region').addClass('hide');
    $('.region-key').addClass('hide');
    $('.map-marker-container').addClass('hide');
    $('.zoom-div.schools').addClass('hide');
    // $('#school-' + regionID).addClass('hide');
    history.replaceState(null, null, location.pathname);
  }


  function schoolShow() {
    $('.zoom-div.schools').removeClass('hide');
    $('#school-' + schoolID).removeClass('hide');
    $('.close-button').removeClass('close-button-region');


    history.replaceState(null, null, '?region=' + regionID + '&school=' + schoolID);
  }


  function schoolHide() {

    $('#school-' + schoolID).addClass('hide');
    $('.zoom-div.schools').addClass('hide');
    $('.zoom-div.region-map').removeClass('hide');
    $('#region-container-' + schoolID).removeClass('hide');
    $('#map-svg-region-' + schoolID).removeClass('hide');
    $('#school-list-' + schoolID).removeClass('hide');
    $('#region-key-' + schoolID).removeClass('hide');
    $('.map-marker-container').removeClass('hide');

    history.replaceState(null, null, '?region=' + regionID);
  }


  //when clicking the close button on a region, hide the region
  function regionCloseBtn() {
    $('.close-regions').click(function () {

      regionHide();

    })
  }

    //when clicking the close button on a school, hide the school
  function schoolCloseBtn() {
    $('.close-schools').click(function () {

      schoolHide();

    })
  }

    //when clicking the close button on a school with no region, hide both
  function bothCloseBtn() {
    $('.close-schools').click(function () {


      schoolHide();
      regionHide();
    })
  }

  // Hide/show functions based on region and school URLs

  function getUrlParam(name) {
    var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
    return (results && results[1]) || undefined;
  }

  var school = getUrlParam('school');
  var region = getUrlParam('region');

  if ((region > '') && (school == undefined)) {
    regionID = url('?region');
    schoolID = url('?school');
    regionShow();
    regionCloseBtn();
  } else if ((region > '') && (school > '')) {
    regionID = url('?region');
    schoolID = url('?school');
    regionShow();
    schoolShow();
    regionCloseBtn();
    schoolCloseBtn();
  } else if ((region == undefined) && (school > '')) {
    regionID = url('?region');
    schoolID = url('?school');
    regionHide();
    schoolShow();
    bothCloseBtn();
  }

  // Show the region when clicking on a part of the overview map
  $('.overview-map-svg-full g').click(function () {

    regionID = $(this).attr('id');

    regionShow();
    regionCloseBtn();

    var scrollValueForAnother = ($('.region-map-svg-container').width() - $(this).width()) * (33 / 100);
    $('.region-map-svg-container').scrollLeft(scrollValueForAnother);

  });

  // Show school info when clicking on a map marker
  $('.map-marker-container:not(.external)').click(function () {

    schoolID = $(this).attr('name');

    schoolShow();
    schoolCloseBtn();

  });

  // Modal show/hide
  $('.modal-toggle').on('click', function(e) {   
    e.preventDefault();
    
    $('#overview-map-svg-container').toggleClass('modal-visible');
    $('.modal').toggleClass('is-visible');
    $('.modal-content').empty().addClass('embed-fluid');
    
    function removeHash () { 
      
      history.pushState('', document.title, window.location.pathname + window.location.search);
      
    }
    
  });
  
  // Handle YouTube Videos
  ( function() {

    var youtube = document.querySelectorAll( '.youtube' );
    
    for (var i = 0; i < youtube.length; i++) {
        
        
        // Append video thumb if not an <a> tag
        if ( youtube[ i ].nodeName === 'DIV' ) {
            
            var source = 'https://img.youtube.com/vi/' + youtube[ i ].dataset.embed + '/hqdefault.jpg';
            var image  = new Image();   
            image.src  = source;
            
          
            
            image.addEventListener( 'load', function() {
                
                youtube[ i ].appendChild( image );
                
            }( i ) );
            
        }
        
        
        youtube[ i ].addEventListener( 'click', function(e) {
            e.preventDefault();
            
            var modal     = document.querySelector( '.modal' );          
            var modalWrap = document.querySelector( '.modal-content' );
            var iframe    = document.createElement( 'iframe' );
            var vidSource = '';
            
            
            if ( this.nodeName === 'A' ) {
                
                vidSource          = this.getAttribute('href') + '?rel=0&autoplay=1';
                var modalContainer = document.getElementById('overview-map-svg-container');
                
                modalContainer.setAttribute( 'class', 'modal-visible' );
             
            } else {
                
                vidSource = 'https://www.youtube.com/embed/' + this.dataset.embed + '?rel=0&autoplay=1';
                
            }
            
            //console.log(vidSource);
            
            iframe.setAttribute( 'frameborder', '0' );
            iframe.setAttribute( 'allowfullscreen', '' );
            iframe.setAttribute( 'allow', 'autoplay' );
            iframe.setAttribute( 'src', vidSource );
            
            modalWrap.appendChild( iframe );
            
            modal.setAttribute('class', 'modal is-visible')
        
        } );	
    }
        
  } )();
  
    // Toggle Investments Modals
    toggleInvestmentModals();
    function toggleInvestmentModals() {
        
        var investments  = $('.list-investments li');
        var projects     = $('.investment-projects .project');
        var modalWrap    = $('.modal-content');
        
        investments.on('click', function() {
            
            var projectId = '#' + $(this).data('project');
            console.log(projectId);
            projects.removeClass('active');
            $(projectId).addClass('active');

            var projectsWrap = $('.investment-projects').clone();
            
            modalWrap.removeClass('embed-fluid').html(projectsWrap);
            
           
            
           
            
            
            
        });
        
        return false;
    }
  
  
  
});