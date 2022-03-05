(function (document){

    var currentTab = null

    document.addEventListener("DOMContentLoaded", start)
    
    function start(){

        const tabs = document.querySelectorAll('[data-tab]');

        for(var i = 0; i < tabs.length; i++){
            tabs[i].addEventListener('click', clickTab.bind(tabs[i]));

            if (i == 0){
                currentTab = tabs[i]
                tabs[i].classList.add('active');
                continue;
            }    

            hideTab(tabs[i].dataset.tab);    
        }
    }

    function clickTab(){

        currentTab.classList.remove('active');
        this.classList.add('active');

        hideTab(currentTab.dataset.tab);

        currentTab = this;
        showTab(currentTab.dataset.tab);
    }

    function showTab(el){
        document.querySelector('[data-tab-content="' + el + '"]').style.display = 'block'
    }

    function hideTab(el){
        document.querySelector('[data-tab-content="' + el + '"]').style.display = 'none'
    }

})(document);
