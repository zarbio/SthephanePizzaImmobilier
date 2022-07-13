
function metreCarre(){
    let checkbox = document.querySelector('#logement_exterieur');
    checkbox.setAttribute('type','hidden');
    checkbox.parentNode.lastChild.setAttribute('style','display:none')

    checkbox.parentNode.insertBefore(document.createElement('input'),checkbox);
    let input = checkbox.parentNode.childNodes[1]
    input.setAttribute('id','check')
    checkbox.parentNode.firstChild.setAttribute('for','check')
    input.setAttribute('type','checkbox')
    input.addEventListener('click',function(){
        if(input.checked === false)
        {
             checkbox.setAttribute('type','hidden');
            checkbox.parentNode.lastChild.setAttribute('style','display:none')
        }
        else{
             checkbox.setAttribute('type','visible');
            checkbox.parentNode.lastChild.setAttribute('style','display:contents')
        }})
}
addUnits();
metreCarre();
function addUnits(){
    let superficie = document.querySelector('#logement_superficie').parentNode
    let exterieur = document.querySelector('#logement_exterieur').parentNode
    let prix = document.querySelector('#logement_prix').parentNode
    superficie.append(document.createElement('label'))
    superficie.lastChild.setAttribute('for','logement_superficie')
    superficie.lastChild.textContent = 'm²'
    exterieur.append(document.createElement('label'))
    exterieur.lastChild.setAttribute('for','logement_exterieur')
   exterieur.lastChild.textContent = 'm²'
    prix.append(document.createElement('label'))
    prix.lastChild.setAttribute('for','logement_prix')
    prix.lastChild.textContent = '€'
}
