//Запрет ввода в поле пробела и символа табуляции
function keyDown(e){
    var position = 'selectionStart' in this ?
        this.selectionStart :
        Math.abs(document.selection.createRange().moveStart('character', -input.value.length)); //ie<9
    if(e.keyCode === 32 && position === 0) return false
}