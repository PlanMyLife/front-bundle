import $ from 'jquery';

class Mq {
    constructor( mqArray ) {
        if ( mqArray.length === 0 ) { return; }

        let self = this;
        self.mqArray = mqArray;

        self.currentMq = self.detectMq();
        window.currentMq = self.currentMq;

        self.initGetters();

        $( window ).on( 'resize', function () {
            self.fireEvents();
        });
    }
}

Mq.prototype = {
    detectMq: function () {
        let self = this,
            currentMq = -1;

        $.each( self.mqArray, function ( index, media ) {
            if ( $( window ).width() < media.size ) {
                currentMq = index;

                return false;
            }
        });

        if ( currentMq === -1 ) {
            currentMq = self.mqArray.length;
        }

        return currentMq;
    },
    fireEvents: function () {
        // PAR DEFAUT : LA TAILLE DE L'ÉCRAN AUGMENTE.
        let futurMq = this.detectMq(),
            direction = 'up:to:';

        if ( futurMq === this.currentMq ) { return false; }

        // EST-CE LA SITUATION PAR DEFAULT ?
        if ( futurMq > this.currentMq ) {
            for ( let i = this.currentMq; i < futurMq; i += 1 ) {
                $( document ).trigger( 'kedge:' + direction + this.mqArray[i].name );
            }
        } else {
            direction = 'down:from:';

            for ( let i = futurMq; i < this.currentMq; i += 1 ) {
                $( document ).trigger( 'kedge:' + direction + this.mqArray[i].name );
            }
        }

        $( document ).trigger( 'kedge:change:mq' );

        this.currentMq = futurMq;
        window.currentMq = this.currentMq;
    },
    getMediaQuery: function ( name ) {
        let self = this,
            mediaByName = null;

        $.each( self.mqArray, function ( index, media ) {
            if ( media.name === name ) {
                mediaByName = media;
            }
        });

        return mediaByName;
    },
    initGetters: function () {
        let self = this;

        window.isUpperThan = function ( name ) {
            if (typeof(name) === 'string') {
                let mediaQuery = self.getMediaQuery(name);
                return $(window).width() > mediaQuery.size;
            } else {
                return $(window).width() > name;
            } 
        };

        window.isLowerThan = function ( name ) {
            if (typeof(name) === 'string') {
                let mediaQuery = self.getMediaQuery(name);
                return $(window).width() <= mediaQuery.size;
            } else {
                return $(window).width() <= name;
            }
        };
    }
};

export default Mq;
