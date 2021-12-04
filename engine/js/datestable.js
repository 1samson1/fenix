/*!
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2016
 * @version 1.3.6
 *
 * Date formatter utility library that allows formatting date/time variables or Date objects using PHP DateTime format.
 * @see http://php.net/manual/en/function.date.php
 *
 * For more JQuery plugins visit http://plugins.krajee.com
 * For more Yii related demos visit http://demos.krajee.com
*/ !function(t,e){"function"==typeof define&&define.amd?define([],e):"object"==typeof module&&module.exports?module.exports=e():t.DateFormatter=e()}("undefined"!=typeof self?self:this,function(){var t,e;return e={DAY:864e5,HOUR:3600,defaults:{dateSettings:{days:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],daysShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],months:["January","February","March","April","May","June","July","August","September","October","November","December"],monthsShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],meridiem:["AM","PM"],ordinal:function(t){var e=t%10,n={1:"st",2:"nd",3:"rd"};return 1!==Math.floor(t%100/10)&&n[e]?n[e]:"th"}},separators:/[ \-+\/.:@]/g,validParts:/[dDjlNSwzWFmMntLoYyaABgGhHisueTIOPZcrU]/g,intParts:/[djwNzmnyYhHgGis]/g,tzParts:/\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,tzClip:/[^-+\dA-Z]/g},getInt:function(t,e){return parseInt(t,e?e:10)},compare:function(t,e){return"string"==typeof t&&"string"==typeof e&&t.toLowerCase()===e.toLowerCase()},lpad:function(t,n,r){var a=t.toString();return r=r||"0",a.length<n?e.lpad(r+a,n):a},merge:function(t){var n,r;for(t=t||{},n=1;n<arguments.length;n++)if(r=arguments[n])for(var a in r)r.hasOwnProperty(a)&&("object"==typeof r[a]?e.merge(t[a],r[a]):t[a]=r[a]);return t},getIndex:function(t,e){for(var n=0;n<e.length;n++)if(e[n].toLowerCase()===t.toLowerCase())return n;return-1}},t=function(t){var n=this,r=e.merge(e.defaults,t);n.dateSettings=r.dateSettings,n.separators=r.separators,n.validParts=r.validParts,n.intParts=r.intParts,n.tzParts=r.tzParts,n.tzClip=r.tzClip},t.prototype={constructor:t,getMonth:function(t){var n,r=this;return n=e.getIndex(t,r.dateSettings.monthsShort)+1,0===n&&(n=e.getIndex(t,r.dateSettings.months)+1),n},parseDate:function(t,n){var r,a,u,i,o,s,c,f,l,d,g=this,h=!1,m=!1,p=g.dateSettings,y={date:null,year:null,month:null,day:null,hour:0,min:0,sec:0};if(!t)return null;if(t instanceof Date)return t;if("U"===n)return u=e.getInt(t),u?new Date(1e3*u):t;switch(typeof t){case"number":return new Date(t);case"string":break;default:return null}if(r=n.match(g.validParts),!r||0===r.length)throw new Error("Invalid date format definition.");for(u=r.length-1;u>=0;u--)"S"===r[u]&&r.splice(u,1);for(a=t.replace(g.separators,"\x00").split("\x00"),u=0;u<a.length;u++)switch(i=a[u],o=e.getInt(i),r[u]){case"y":case"Y":if(!o)return null;l=i.length,y.year=2===l?e.getInt((70>o?"20":"19")+i):o,h=!0;break;case"m":case"n":case"M":case"F":if(isNaN(o)){if(s=g.getMonth(i),!(s>0))return null;y.month=s}else{if(!(o>=1&&12>=o))return null;y.month=o}h=!0;break;case"d":case"j":if(!(o>=1&&31>=o))return null;y.day=o,h=!0;break;case"g":case"h":if(c=r.indexOf("a")>-1?r.indexOf("a"):r.indexOf("A")>-1?r.indexOf("A"):-1,d=a[c],-1!==c)f=e.compare(d,p.meridiem[0])?0:e.compare(d,p.meridiem[1])?12:-1,o>=1&&12>=o&&-1!==f?y.hour=o%12===0?f:o+f:o>=0&&23>=o&&(y.hour=o);else{if(!(o>=0&&23>=o))return null;y.hour=o}m=!0;break;case"G":case"H":if(!(o>=0&&23>=o))return null;y.hour=o,m=!0;break;case"i":if(!(o>=0&&59>=o))return null;y.min=o,m=!0;break;case"s":if(!(o>=0&&59>=o))return null;y.sec=o,m=!0}if(h===!0){var D=y.year||0,v=y.month?y.month-1:0,S=y.day||1;y.date=new Date(D,v,S,y.hour,y.min,y.sec,0)}else{if(m!==!0)return null;y.date=new Date(0,0,0,y.hour,y.min,y.sec,0)}return y.date},guessDate:function(t,n){if("string"!=typeof t)return t;var r,a,u,i,o,s,c=this,f=t.replace(c.separators,"\x00").split("\x00"),l=/^[djmn]/g,d=n.match(c.validParts),g=new Date,h=0;if(!l.test(d[0]))return t;for(u=0;u<f.length;u++){if(h=2,o=f[u],s=e.getInt(o.substr(0,2)),isNaN(s))return null;switch(u){case 0:"m"===d[0]||"n"===d[0]?g.setMonth(s-1):g.setDate(s);break;case 1:"m"===d[0]||"n"===d[0]?g.setDate(s):g.setMonth(s-1);break;case 2:if(a=g.getFullYear(),r=o.length,h=4>r?r:4,a=e.getInt(4>r?a.toString().substr(0,4-r)+o:o.substr(0,4)),!a)return null;g.setFullYear(a);break;case 3:g.setHours(s);break;case 4:g.setMinutes(s);break;case 5:g.setSeconds(s)}i=o.substr(h),i.length>0&&f.splice(u+1,0,i)}return g},parseFormat:function(t,n){var r,a=this,u=a.dateSettings,i=/\\?(.?)/gi,o=function(t,e){return r[t]?r[t]():e};return r={d:function(){return e.lpad(r.j(),2)},D:function(){return u.daysShort[r.w()]},j:function(){return n.getDate()},l:function(){return u.days[r.w()]},N:function(){return r.w()||7},w:function(){return n.getDay()},z:function(){var t=new Date(r.Y(),r.n()-1,r.j()),n=new Date(r.Y(),0,1);return Math.round((t-n)/e.DAY)},W:function(){var t=new Date(r.Y(),r.n()-1,r.j()-r.N()+3),n=new Date(t.getFullYear(),0,4);return e.lpad(1+Math.round((t-n)/e.DAY/7),2)},F:function(){return u.months[n.getMonth()]},m:function(){return e.lpad(r.n(),2)},M:function(){return u.monthsShort[n.getMonth()]},n:function(){return n.getMonth()+1},t:function(){return new Date(r.Y(),r.n(),0).getDate()},L:function(){var t=r.Y();return t%4===0&&t%100!==0||t%400===0?1:0},o:function(){var t=r.n(),e=r.W(),n=r.Y();return n+(12===t&&9>e?1:1===t&&e>9?-1:0)},Y:function(){return n.getFullYear()},y:function(){return r.Y().toString().slice(-2)},a:function(){return r.A().toLowerCase()},A:function(){var t=r.G()<12?0:1;return u.meridiem[t]},B:function(){var t=n.getUTCHours()*e.HOUR,r=60*n.getUTCMinutes(),a=n.getUTCSeconds();return e.lpad(Math.floor((t+r+a+e.HOUR)/86.4)%1e3,3)},g:function(){return r.G()%12||12},G:function(){return n.getHours()},h:function(){return e.lpad(r.g(),2)},H:function(){return e.lpad(r.G(),2)},i:function(){return e.lpad(n.getMinutes(),2)},s:function(){return e.lpad(n.getSeconds(),2)},u:function(){return e.lpad(1e3*n.getMilliseconds(),6)},e:function(){var t=/\((.*)\)/.exec(String(n))[1];return t||"Coordinated Universal Time"},I:function(){var t=new Date(r.Y(),0),e=Date.UTC(r.Y(),0),n=new Date(r.Y(),6),a=Date.UTC(r.Y(),6);return t-e!==n-a?1:0},O:function(){var t=n.getTimezoneOffset(),r=Math.abs(t);return(t>0?"-":"+")+e.lpad(100*Math.floor(r/60)+r%60,4)},P:function(){var t=r.O();return t.substr(0,3)+":"+t.substr(3,2)},T:function(){var t=(String(n).match(a.tzParts)||[""]).pop().replace(a.tzClip,"");return t||"UTC"},Z:function(){return 60*-n.getTimezoneOffset()},c:function(){return"Y-m-d\\TH:i:sP".replace(i,o)},r:function(){return"D, d M Y H:i:s O".replace(i,o)},U:function(){return n.getTime()/1e3||0}},o(t,t)},formatDate:function(t,n){var r,a,u,i,o,s=this,c="",f="\\";if("string"==typeof t&&(t=s.parseDate(t,n),!t))return null;if(t instanceof Date){for(u=n.length,r=0;u>r;r++)o=n.charAt(r),"S"!==o&&o!==f&&(r>0&&n.charAt(r-1)===f?c+=o:(i=s.parseFormat(o,t),r!==u-1&&s.intParts.test(o)&&"S"===n.charAt(r+1)&&(a=e.getInt(i)||0,i+=s.dateSettings.ordinal(a)),c+=i));return c}return""}},t});
/* 
 * Datestable v.1.0
 * Autor SAMSON
*/

;(function (window, $, undefined) { 

;(function () {

    var pluginName = 'datestable',
        $body,
        baseTemplate = '' +
            '<div class="datestable">' +
            '<div class="datestable--nav"></div>' +
            '<div class="datestable--content"></div>' +
            '</div>',
        defaults = {
            // Datestable
            timestamp: false,
            disabled: false,

            // Dates
            minDate: '',
            maxDate: '',
            dateFormat: '',

            // Time
            minTime: 9,
            maxTime: 17,
            stepTime: 30,

            // Locale
            language: 'ru',

            // Navigation
            prevHtml: '<svg><path d="M 17,12 l -5,5 l 5,5"></path></svg>',
            nextHtml: '<svg><path d="M 14,12 l 5,5 l -5,5"></path></svg>',

            // Events
            onChangeRange: '',
            onRenderTime: '',
        },
        datestable;
    
    var Datestable = function (el, options) {
        this.el = el;
        this.$el = $(el);

        this.opts = $.extend(true, {}, defaults, options, this.$el.data());

        if ($body == undefined) {
            $body = $('body');
        }

        if (!this.opts.startDate) {
            this.opts.startDate = new Date();
        }       
        
        
        this.disabled = this.opts.disabled;
        this.inited = false;        
        this.silent = false; // Need to prevent unnecessary rendering

        this.currentDate = this.opts.startDate;
        this.selectedDate = null;
        this.range = {};
        this._createShortCuts();

        this.init();
    }

    datestable = Datestable;

    datestable.prototype = {
        init: function (){

            // Hide input
            this.$el.hide()
            
            this._buildBaseHtml();
            this._defineLocale(this.opts.language);
            this.initDateFormater();
            this._syncWithMinMaxDates();

            this.body = new $.fn.datestable.Body(this, this.opts);
            this.nav = new $.fn.datestable.Navigation(this, this.opts);

            this.inited = true;
        },

        update: function(){
            if(!this.inited) return false;

            this.body.update();
            this.nav.update();
        },

        initDateFormater: function(){
            var value = this.$el.val();

            this.dateFormater = new DateFormatter();
            this.df = this.dateFormater;

            if(value){

                if(!(/\D/g.test(value))){
                    value = parseInt(value);
                    this.selectDate(this.df.parseDate(value));
                    return;
                }

                this.selectDate(this.df.parseDate(value, this.loc.dateFormat))
            }
        },

        _createShortCuts: function () {
            this.minDate = this.opts.minDate ? this.opts.minDate : new Date(-8639999913600000);
            this.maxDate = this.opts.maxDate ? this.opts.maxDate : new Date(8639999913600000);
        },

        _defineLocale: function (lang) {
            if (typeof lang == 'string') {
                this.loc = $.fn.datestable.language[lang];
                if (!this.loc) {
                    console.warn('Can\'t find language "' + lang + '" in datestable.language, will use "ru" instead');
                    this.loc = $.extend(true, {}, $.fn.datestable.language.ru)
                }

                this.loc = $.extend(true, {}, $.fn.datestable.language.ru, $.fn.datestable.language[lang])
            } else {
                this.loc = $.extend(true, {}, $.fn.datestable.language.ru, lang)
            }

            if (this.opts.dateFormat) {
                this.loc.dateFormat = this.opts.dateFormat
            }
        },

        _buildBaseHtml: function () {
            var $appendTarget,
                $inline = $('<div class="datestable-inline">');

            if(this.el.nodeName == 'INPUT') {                
                $appendTarget = $inline.insertAfter(this.$el);
            } else {
                $appendTarget = $inline.appendTo(this.$el);
            }

            this.$datestable = $(baseTemplate).appendTo($appendTarget);
            this.$content = $('.datestable--content', this.$datestable);
            this.$nav = $('.datestable--nav', this.$datestable);

                    
        },

        _syncWithMinMaxDates: function () {
            var curTime = this.date.getTime();
            
            this.silent = true;
            if (this.minTime > curTime) {
                this.date = this.minDate;
            }

            if (this.maxTime < curTime) {
                this.date = this.maxDate;
            }
            this.silent = false;
        },

        _isInRange: function (date) {
            var time = date.getTime();

            return time >= this.minTime && time <= this.maxTime
        },

        _isSelected: function (date) {
            return datestable.isSome(this.selectedDate, date);
        },

        _setInputValue: function() {
            var date = this.selectedDate,
                dateFormat = this.loc.dateFormat;

            if(date){
                if(this.opts.timestamp){
                    this.$el.val(date.getTime());
                    return;
                }

                this.$el.val(this.df.formatDate(date, dateFormat));
                return;
            }
            this.$el.val('');
        },

        prev: function(){
            var d = this.parsedDate;
            this.date = new Date(d.year, d.month, d.date - 7);
        },

        next: function(){
            var d = this.parsedDate;
            this.date = new Date(d.year, d.month, d.date + 7);
        },
        
        set date (val) {
            if (!(val instanceof Date)) return;

            this.currentDate = val;

            if (this.inited && !this.silent) {
                this.body._render();
                this.nav._render();
            }
            
            return val;
        },

        selectDate: function(date){
            if (!(date instanceof Date)) return;

            this.selectedDate = date;            

            this._setInputValue();
        }, 
        
        removeDate: function(){
            this.selectedDate = null;

            this._setInputValue();
        },

        get date () {
            return this.currentDate;
        },

        get parsedDate (){
            return datestable.getParsedDate(this.date);
        },

        get minTime() {
            var min = datestable.getParsedDate(this.minDate);
            return new Date(min.year, min.month, min.date).getTime();
        },

        get maxTime() {
            var max = datestable.getParsedDate(this.maxDate);
            return new Date(max.year, max.month, max.date).getTime();
        },
    }
    datestable.getDaysCount = function (date) {
        return new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
    };

    datestable.firstZero = function (value){
        return ('0' + value).slice(-2);
    }

    datestable.isSome = function (date1, date2, type){
        if (!date1 || !date2) return false;

        var d1 = datestable.getParsedDate(date1),
            d2 = datestable.getParsedDate(date2),
            _type = type ? type : 'full',

        conditions = {
            short: d1.date == d2.date && d1.month == d2.month && d1.year == d2.year,
            full: d1.minutes == d2.minutes && d1.hours == d2.hours && d1.date == d2.date && d1.month == d2.month && d1.year == d2.year,
        };

        return conditions[_type];
    }

    datestable.getParsedDate = function (date) {
        return {
            year: date.getFullYear(),
            month: date.getMonth(),
            fullMonth: (date.getMonth() + 1) < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1, // One based
            date: date.getDate(),
            fullDate: date.getDate() < 10 ? '0' + date.getDate() : date.getDate(),
            day: date.getDay(),
            hours: date.getHours(),
            fullHours:  date.getHours() < 10 ? '0' + date.getHours() :  date.getHours() ,
            minutes: date.getMinutes(),
            fullMinutes:  date.getMinutes() < 10 ? '0' + date.getMinutes() :  date.getMinutes(),
            time: date.getTime(),
        }
    };

    $.fn.datestable = function ( options ) {
        return this.each(function () {
            if (!$.data(this, pluginName)) {
                if(this.nodeName == 'INPUT'){
                    $.data(this,  pluginName, new Datestable( this, options ));
                } else {
                    console.error('This DOM element is not a input!', this)
                }
            } else {
                var _this = $.data(this, pluginName);

                _this.opts = $.extend(true, _this.opts, options);
                _this.update();
            }
        });
    };

    $.fn.datestable.Constructor = Datestable;

    $.fn.datestable.language = {
        ru: {
            days: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
            daysShort: ['Вос','Пон','Вто','Сре','Чет','Пят','Суб'],
            daysMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
            months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            dateMonth: ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'],
            monthsShort: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'],
            today: 'Сегодня',
            currentWeek: 'Текущая неделя',
            clear: 'Очистить',
            dateFormat: 'd.m.Y H:i',
            prev: 'Предыдущая',
            next: 'Следующая',
            firstDay: 1
        }
    };

})();

// Body
;(function () {
    var templates = {
            day : '' + 
            '<div class="datestable--day">' +
            '<div class="datestable--day-name"></div>' +
            '<div class="datestable--day-times"></div>' +
            '</div>',
            time : '<div class="datestable--day-time">' + 
            '<button type="button"></button>' +
            '</div>',
           
        },
        datestable = $.fn.datestable,
        dp = datestable.Constructor;

    datestable.Body = function (d, opts) {
        this.d = d;
        this.opts = opts;       

        this.init();
    };

    datestable.Body.prototype = {
        init: function () {
            this._buildBaseHtml();
            this._render();

            this._bindEvents();
        },

        update: function (){
            var $times = $('.datestable--day-time', this.$el),
                _this = this,
                $time,
                $button,
                date;
            $times.each(function (time, i) {
                $time = $(this);
                $button = $('.datestable--btn', $time);
                date = $button.data('date');
                $button.attr('class', _this._getContentsTimes(date));
            });

        },

        _buildBaseHtml: function () {
            this.$el = this.d.$content;
        },

        _render: function (){
            if(this.$el.html()){
                this.$el.html('');
            }
                
            this._renderDays(this.d.currentDate);
        },

        _renderDays: function (date){
            var offsetDayWeek = date.getDay() >= this.d.loc.firstDay ? 0 : 7
                firstDayWeek = date.getDate() - date.getDay() + this.d.loc.firstDay - offsetDayWeek,
                lastDayWeek = firstDayWeek + 6;

            this.d.range.beginDate = new Date(date.getFullYear(), date.getMonth(), firstDayWeek);
            this.d.range.endDate = new Date(date.getFullYear(), date.getMonth(), lastDayWeek, 23, 59, 59);
            if(this.opts.onChangeRange){
                this.opts.onChangeRange(this.d.range.beginDate, this.d.range.endDate, this.d);
            }

            for (var i = firstDayWeek; i <= lastDayWeek; i++){
                this.$el.append( this._getDay( new Date( date.getFullYear(), date.getMonth(), i) ) );
            }
        },

        _getDay: function (date){
            var $day = $(templates['day']),
                $name = $('.datestable--day-name', $day),
                $times = $('.datestable--day-times', $day);

                $name.text(this.d.loc.daysMin[date.getDay()] + ' ' + date.getDate())

                for (var hours = this.opts.minTime; hours <= this.opts.maxTime; hours++){
                    for (var minutes = 0; minutes <= this.opts.stepTime; minutes += this.opts.stepTime){
                        $times.append(this._getTimes( new Date(
                            date.getFullYear(),
                            date.getMonth(),
                            date.getDate(),
                            hours,
                            minutes,
                        )));
                    }
                }

            return $day;
        },

        _getContentsTimes: function(date){
            var dateTime = date.getTime(),
                minTime = this.d.minDate.getTime(),
                maxTime = this.d.maxDate.getTime(),
                classes = 'datestable--btn -large-'
                render = {
                    disabled: false,
                    busy: false,
                };

            if(this.opts.onRenderTime){
                render = $.extend(render, this.opts.onRenderTime(date));
            }

            render.disabled = (minTime >= dateTime || dateTime > maxTime) ? true : render.disabled;

            if(render.disabled || this.d.disabled){
                classes += ' -disabled-';
            }

            if(render.busy){
                classes += ' -peach-';
            }

            if(!render.disabled && !render.busy){
                classes += ' -green-';
            }                

            if(this.d._isSelected(date)){
                classes += ' -select-';
            }

            return classes;            
        },

        _getTimes: function (date){

            $time = $(templates['time']),
            $button = $('button', $time);

            $button.attr('class' ,this._getContentsTimes(date));

            $button.data('date', date);
            $button.text(dp.firstZero(date.getHours()) + ':' + dp.firstZero(date.getMinutes()));

            return $time;

        },

        // Events

        _bindEvents: function(){
            this.$el.on('click', '.datestable--btn', this._onClickTime.bind(this))
        },

        _onClickTime: function(e){
            var $el = $(e.target),
                date = $el.data('date');

            if($el.hasClass('-disabled-')) return;
            
            if(!$el.hasClass('-select-')){
                this.d.selectDate(date);

                $('.-select-' , this.$el).removeClass('-select-');
                $el.addClass('-select-');
            } else {
                this.d.removeDate();

                $el.removeClass('-select-');
            }    
        },
    };
})();

//Navigation
;(function () {
    var template = '' +
        '<button type="button" class="datestable--btn" data-action="prev"></button>' +
        '<div class="datestable--nav-range">'+
        '<div class="datestable--nav-current"></div>' +
        '<div class="datestable--nav-title"></div>'+
        '</div>' +
        '<button type="button" class="datestable--btn" data-action="next"></button>',
        datestable = $.fn.datestable,
        dp = datestable.Constructor;

    datestable.Navigation = function (d, opts) {
        this.d = d;
        this.opts = opts;

        this.init();
    };

    datestable.Navigation.prototype = {
        init: function () {
            this._buildBaseHtml();
            this._bindEvents();
        },

        update: function(){
            var d = dp.getParsedDate(this.d.date);

            if(this.d._isInRange(new Date(d.year, d.month, d.date - 7)) && !this.d.disabled){
                this.$prev.removeClass('-disabled-');
            } else {
                this.$prev.addClass('-disabled-');
            }
            
            if(this.d._isInRange(new Date(d.year, d.month, d.date + 7)) && !this.d.disabled){
                this.$next.removeClass('-disabled-');
            } else {
                this.$next.addClass('-disabled-');
            }
        },
        
        _buildBaseHtml: function(){
            this._render();
        },

        _render: function(){
            var currentTime = new Date().getTime(),
                beginTime = this.d.range.beginDate.getTime(),
                endTime = this.d.range.endDate.getTime(),
                d = dp.getParsedDate(this.d.date);

            this.$nav = this.d.$nav;
            this.$nav.html(template);

            this.$title = $('.datestable--nav-title', this.$nav).text(this._getTitle());
            this.$current = $('.datestable--nav-current', this.$nav).text(this.d.loc.currentWeek);
            if(beginTime > currentTime || currentTime > endTime){
                this.$current.hide();
            }
            
            this.$prev = $('.datestable--btn[data-action="prev"]', this.$nav);
            $(this.opts.prevHtml).appendTo(this.$prev);
            $('<span>').text(this.d.loc.prev).appendTo(this.$prev);
            if(!this.d._isInRange(new Date(d.year, d.month, d.date - 7)) || this.d.disabled){
                this.$prev.addClass('-disabled-');
            }
            
            this.$next = $('.datestable--btn[data-action="next"]', this.$nav);
            $(this.opts.nextHtml).appendTo(this.$next);
            $('<span>').text(this.d.loc.next).appendTo(this.$next);
            if(!this.d._isInRange(new Date(d.year, d.month, d.date + 7)) || this.d.disabled){
                this.$next.addClass('-disabled-');
            }
        },

        _getTitle: function(){
            var range = this.d.range,
                start = dp.getParsedDate(range.beginDate),
                end = dp.getParsedDate(range.endDate),
                text = `${start.date} ${this.d.loc.dateMonth[start.month]} - ${end.date} ${this.d.loc.dateMonth[end.month]}`;

            return text;
        },

        // Events

        _bindEvents: function(){
            this.$nav.on('click', '.datestable--btn', this._onClickNavButton.bind(this));
        },

        _onClickNavButton: function (e) {
            var $el = $(e.target).closest('[data-action]'),
                action = $el.data('action');

            if ($el.hasClass('-disabled-')) return;
            
            this.d[action]();
        },
    }

})();

})(window, jQuery);