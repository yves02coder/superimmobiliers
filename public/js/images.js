window.onload= () =>{
    //gestion des boutons "supprimer"
    let links= document.querySelectorAll("[data-delete]")
    //console.log(links)
    //on va boucler sur links

    for (link of links){
        //on ecoute le clic
        link.addEventListener("click", function (e){
            e.preventDefault()
            //on demande confirmation
            if (confirm("Voulez vous supprimer cette image?")){
                //on envoie une requete ajax vers le href du lien avec la methode delete
                fetch(this.getAttribute("href"),{
                    method:"DELETE",
                    headers:{
                        "X-Requested-With": "XMLHttpRequest",
                        "Content-Type":"application/json"
                    },
                    body:JSON.stringify({"_token": this.dataset.token})

                }).then(
                    //on recupère la reponse en json
                    response=>response.json()
                ).then(data=>{
                    if (data.success)
                        //on supprime l'element parent
                        this.parentElement.remove()
                        else
                            alert(data.error)

                }).catch(e=>alert(e))
            }
        })
    }
}