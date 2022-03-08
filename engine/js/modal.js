class ModalWindow{

    static instance    

    static init(){
        if(this.instance == undefined)
            return this.instance = new this()
        
        return this.instance
    }

    constructor(){
        this.generate = false
    }

    
    open(text, callback) {

        if(callback){
            this.callback = callback
        } else {
            this.callback = () => {
                console.log('Modal window opened without callback');
            }
        }
        
        $('body').css('overflow','hidden')
        
        if(!this.generate){
            this.generateHTML()
        }
        else{
            this.$template.show()
        }

        $('.mw-text', this.$template).text(text)
    }

    close(){
        $('body').css('overflow','')
        this.$template.hide()
    }

    generateHTML() {
        this.$template = $('<div class="mw-box">')

        let $window = $('<div class="mw-window">').append(`
            <div class="mw-header">
                <div class="mw-header__title">Подтверждение</div>
                <div class="mw-header__close">
                    <svg width="24" height="24"><path d="M17.3 8.2L13.4 12l3.9 3.8a1 1 0 01-1.5 1.5L12 13.4l-3.8 3.9a1 1 0 01-1.5-1.5l3.9-3.8-3.9-3.8a1 1 0 011.5-1.5l3.8 3.9 3.8-3.9a1 1 0 011.5 1.5z" fill-rule="evenodd"></path></svg>
                </div>
            </div>
            <div class="mw-text"></div>
            <div class="mw-actions">
                <button class="mw-btn mw-btn-red mw-cansel">Нет</button>
                <button class="mw-btn mw-ok">Да</button>
            </div>
            
        `)

        this.generateEvents($window)
        this.$template.append($window)
        this.$template.append('<div class="mw-wrap">')
        
        this.$template.appendTo('body')
        this.generate = true
    }

    generateEvents ($window){

        /* Events */    
        var _this = this

        $($window).on('click', '.mw-header__close', this, function () {
            _this.close()
        })

        $($window).on('click', '.mw-cansel', this, function () {
            _this.close()
        })    
        
        $($window).on('click', '.mw-ok', this, function () {
            _this.action()
        })
    }

    action(){
        this.callback()
        this.close()
    }
}
