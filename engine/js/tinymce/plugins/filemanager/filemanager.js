class FileManager{

    constructor(){
        this.generate = false
    }

    
    open() {
        
        if(!this.generate){
            
            this.generateHTML()
            
        }
        else{
            this.$template.show()
        }
    }

    generateHTML() {
        this.$template = $('<div class="fm-box">')

            let $window = $('<div class="fm-window">')
                .append(`
                    <div class="fm-header">
                        <div class="fm-header__title">Файловый менеджер</div>
                        <div class="fm-header__close">
                            <svg width="24" height="24"><path d="M17.3 8.2L13.4 12l3.9 3.8a1 1 0 01-1.5 1.5L12 13.4l-3.8 3.9a1 1 0 01-1.5-1.5l3.9-3.8-3.9-3.8a1 1 0 011.5-1.5l3.8 3.9 3.8-3.9a1 1 0 011.5 1.5z" fill-rule="evenodd"></path></svg>
                        </div>
                    </div>
                    <div class="drop-area">
                        <form action="" method="post" enctype="multipart/form-data">
                            <label for="upload_files" class="fm-drag-drop">
                                <div> Перетащите файлы сюда </div>
                                <div> — или — </div>
                                <div class="fm-btn fm-btn-secondary"> Выберите файл на компьютере </div>
                            </label>
                            <input type="file" name="upload_files" id="upload_files" multiple>
                        </form>
                    </div>
                    <div class="fm-previews"></div>
                    <div class="fm-actions">
                        <button class="fm-btn fm-btn-secondary">Отмена</button>
                        <button class="fm-btn">Вставить</button>
                    </div>
                    
                `)

            this.previews = $window.children('.fm-previews'),
            this.generateEvents($window)
            this.$template.append($window)
            this.$template.append('<div class="fm-wrap">')
            
            this.$template.appendTo('body')
            this.generate = true
    }

    generateEvents ($window){

        /* Events drop files */

        let drop_area = $($window).children('.drop-area')

        $(drop_area).on('dragenter', drop_area, function (event) {
            event.preventDefault()            
            event.data.addClass('drop-area-droped')
        })
        $(drop_area).on('dragover', drop_area, function (event) {
            event.preventDefault()
            event.data.addClass('drop-area-droped')
           
        })
        $(drop_area).on('dragleave', drop_area, function (event) {
            event.preventDefault()
            event.data.removeClass('drop-area-droped')
        })
        $(drop_area).on('drop', {drop_area, fm:this}, function (event) {
            event.preventDefault()
            event.data.drop_area.removeClass('drop-area-droped')
            
            let dt = event.originalEvent.dataTransfer
            let files = dt.files

            event.data.fm.handleFiles(files)
        })

        $($window).on('input', '#upload_files', {fm:this}, function (event) {
            event.data.fm.handleFiles(this.files)
        })

        $($window).on('click', '.fm-header__close', this, function (event) {
            $(event.data.$template).hide()
        })

        
    }

    handleFiles(files){
        ([...files]).forEach( file => {
            this.uploadFile(file)
        })
    }

    uploadFile(file){
        let url = '/api/upload/'
        let formData = new FormData()

        let preview = this.createPreview(file)

        formData.append('file', file)
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then( response => response.json())
        .then(json => {
            preview.status.addClass('fm-success')
            console.log(json)
        })
        .catch(() => { 
            preview.status.addClass('fm-error')
            console.log('error')
            /* Ошибка. Информируем пользователя */
        })
    }

    createPreview(file){
        let preview = $(`
            <div class="fm-preview">
                <div class="fm-preview-uploadimage">
                    <img class="fm-preview__image">
                </div>
                <div class="fm-preview__title"></div>
                <div class="fm-preview__status"></div>
            </div>
        `)
        
        let title = preview.children('.fm-preview__title'),
            image = preview.find('.fm-preview__image'),
            status = preview.children('.fm-preview__status'),
            reader = new FileReader();
            
        reader.readAsDataURL(file)
        reader.onloadend = () => {
            title.text(file.name)
            image.attr('src', reader.result)
            this.previews.append(preview)
        }


        return {
            title,
            status
        }
    }
}
