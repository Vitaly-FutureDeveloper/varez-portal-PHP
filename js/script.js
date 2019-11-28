//Программирование админки
window.addEventListener('DOMContentLoaded', function (){

    var adminPaste = document.querySelector(".form-insert-paste"),
        paste = document.querySelector(".past-form");

    adminPaste.classList.add('hide'); //Скроем админку для изменения контента

    //Событие удаления hide при нажатии на заголовок Править страницу:
    paste.addEventListener('click', function () {
        if( !paste.classList.contains("open") ){
            adminPaste.classList.remove('hide');
            paste.classList.add('open'); //Задание атрибута, что блок открыт
            paste.innerHTML = "Закрыть окно правки";
        }
        else {
            adminPaste.classList.add('hide');
            paste.classList.remove('open'); //Задание атрибута, что блок закрыт
            paste.innerHTML = "Править страницу";
        }
    });

});