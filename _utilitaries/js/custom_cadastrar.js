// function js_sumir(){
//     var teste = document.querySelector("#c1")
//     for(var i=1;i<5;i++){
//         document.getElementById("c"+i).hidden=true;
//     }
//     var obj=teste.target.dataset.nome;
//      document.getElementById(obj).hidden=false;
// }
// function inicia(){
//     for(var i=1;i<5;i++){
//         document.getElementById("c"+i).hidden=true;
//     }
//     document.getElementById("c"+i).addEventListener("click", function (){(js_sumir);});
//     document.getElementById("c"+i).addEventListener("click", js_sumir);
//     document.getElementById("c"+i).addEventListener("click", js_sumir);
//     document.getElementById("c"+i).addEventListener("click", js_sumir);
    
// }



function js_sumir(obj){
    document.getElementById(obj).hidden=true;
}