/* globals Waypoint */
import $ from 'jquery';
import '../libs/waypoints';

class Generic {
    constructor () {
        let self = this;
        this.close = $( '.link-close' );
        this.isOnMove = false;
        this.backToTop = $( '.back-to-top' );
        this.onScroll = false;

        this.initBlockLinks();

        if ( this.close.length > 0 ) {
            this.initCloseLink();
        }

        $( document ).on( 'pointerdown', function () {
            self.isOnMove = false;

            $( document ).on( 'pointermove', function () {
                self.isOnMove = true;
            });
        });

        $( document ).on( 'pointerup', function () {
            $( document ).off( 'pointermove' );
        });

        this.backToTopWaypoint = new Waypoint({
            element: document.getElementById( 'main' ),
            handler: function ( direction ) {
                if ( direction === 'down' ) {
                    self.backToTop.addClass( 'active' );
                } else {
                    self.backToTop.removeClass( 'active' );
                }
            },
            offset: -50
        });

        self.backToTop.on( 'click', function ( ev ) {
            ev.preventDefault();
            return false;
        });

        self.backToTop.on( 'pointerup', function () {
            self.scrollToTop();

            $( this ).blur();
        });

        $( document ).on( 'click', '.btn-go-to, .link-go-to', function ( ev ) {
            ev.preventDefault();
            return false;
        }).on( 'pointerup', '.btn-go-to, .link-go-to', function ( ev ) {
            let target = $( $( this ).attr( 'href' ) );

            self.scrollToElement( target );
        });
    }
}

Generic.prototype = {
    initBlockLinks: function () {
        let self = this;

        $( document ).on( 'pointerup', '.block-link', function () {
            if ( self.isOnMove ) { return; }

            let $this = $( this );
            let link = $this.find( 'a' );

            if ( typeof link.attr( 'target' ) !== 'undefined' ) {
                window.open( link.attr( 'href' ), link.attr( 'target' ) );
            } else {
                document.location = link.attr( 'href' );
            }
        });
    },
    getCookie: function ( cname )    {
        var name = cname + '=';
        var ca = document.cookie.split( ';' );
        for ( var i = 0; i < ca.length; i += 1 )        {
            var c = ca[i].trim();
            if ( c.indexOf( name ) === 0 ) { return c.substring( name.length, c.length ); }
        }
        return false;
    },
    setCookie: function ( cname,cvalue,exdays, isDomain = false )    {
        let d = new Date();
        let expires = 0;

        if ( exdays !== 0 ) {
            d.setTime( d.getTime() + exdays * 24 * 60 * 60 * 1000 );
            expires = 'expires=' + d.toGMTString();
        } else {
            expires = 0;
        }

        let cookie = cname + '=' + cvalue + '; path=/; ' + expires;

        if ( isDomain ) {
            let domainElements = window.location.hostname.split( '.' );
            let domain = domainElements.slice( -2, domainElements.length ).join( '.' );
            cookie = cookie + '; domain=' + domain;
        }

        document.cookie = cookie;
    },
    initCloseLink: function () {
        let self = this;

        $.each( self.close, function () {
            let $target = $( $( this ).attr( 'href' ) );

            if ( $( this ).hasClass( 'link-close-cookie' ) ) {
                if ( self.getCookie( $target.attr( 'id' ) ) === 'true' ) {
                    return $target.remove();
                } else {
                    $target.removeClass( 'hide' );
                }
            }
        });

        $( 'body' ).on( 'click', '.link-close', function ( ev ) {
            ev.preventDefault();
            return false;
        });

        $( 'body' ).on( 'pointerup', '.link-close', function () {
            if ( self.isOnMove ) { return; }

            let $target = $( $( this ).attr( 'href' ) );

            if ( $( this ).hasClass( 'link-close-cookie' ) ) {
                let days = 30;

                if ( typeof $( this ).data( 'days' ) !== 'undefined' ) {
                    days = $( this ).data( 'days' );
                }
                
                self.setCookie( $target.attr( 'id' ), true, days );
            }

            if ( $target.length > 0 ) {
                $target.slideUp( 300, function () {
                    $target.remove();
                });
            }
        });
    },
    scrollToElement: function ( $element ) {
        var self = this;
        if ( self.onScroll ) { return; } //Prevent multi call at the same time
        self.onScroll = true;

        var dest;
        if ( $element.offset().top > $( document ).height() - $( window ).height() ) {
            dest = $( document ).height() - $( window ).height();
        } else {
            dest = $element.offset().top;
        }

        /*if ( !$( '#main' ).hasClass( 'programme' ) ) {
            if ( dest > $( '#main' ).offset().top && window.isUpperThan( 'tablet' ) ) {
                dest = dest - 90; //PREVENT STICKY
            }
        } else {
            if ( dest > $( '#programmeContent' ).offset().top && window.isUpperThan( 'tablet' ) ) {
                dest = dest - 100; //PREVENT STICKY
            }
        }*/

        //go to destination
        $( 'html,body' ).animate({
            scrollTop: dest
        }, 800, 'swing', function () {
            self.onScroll = false;
        });
    },
    scrollToTop: function () {
        var self = this;

        if ( self.onScroll ) { return; } //Prevent multi call at the same time

        self.onScroll = true;

        if ( typeof window.nav !== 'undefined' && window.isUpperThan( 'tablet' ) ) {
            window.nav.removeSticky();
        }

        //go to destination
        $( 'html,body' ).animate({
            scrollTop: 0
        }, 500, function () { self.onScroll = false; });
    },
    isDescendant: function ( parent, child ) {
        let node = child.parentNode;
        while ( node != null ) {
            if ( node == parent ) {
                return true;
            }
            node = node.parentNode;
        }
        return false;
    }
};

export default Generic;
