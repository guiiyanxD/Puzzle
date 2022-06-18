// const puzzle = document.getElementById('puzzle');
// const piezas_container = document.getElementById('pieces');
//
// const game = {{\Illuminate\Support\Js::from($game)}};
// const cant_cols = game[0].cols;
// const cant_rows = game[0].rows;
//
// const pieces = {{\Illuminate\Support\Js::from($images)}};
// const width = pieces[1].width;
// const height = pieces[1].height;
//
//
// for( let i = 0; i <cant_rows; i++ ){
//     const divv = document.createElement("div");
//     divv.classList.add('row');
//     puzzle.appendChild(divv);
//
//     for(let j =0 ; j<cant_cols;j++){
//         const div = document.createElement("div");
//         div.classList.add('col');
//         div.setAttribute('id',i.toString() + j.toString());
//         div.classList.add('my_placeholder');
//         div.style.width = width.toString() + 'px';
//         div.style.height = height.toString() + 'px';
//         div.style.margin = '1px';
//         div.style.border = '1px solid #000000';
//         puzzle.appendChild(div);
//     }
// }
//
// piezas_container.addEventListener('dragstart',e=>{
//     e.dataTransfer.setData('id', e.target.id);
//     console.log(e.target.id);
// });
// puzzle.addEventListener('dragover', e =>{
//     e.preventDefault();
//     e.target.classList.add('hover');
// });
//
// puzzle.addEventListener('dragleave', e=>{
//     e.target.classList.remove('hover');
// });
//
// puzzle.addEventListener('drop',e =>{
//     e.target.classList.remove('hover');
//
//     const id = e.dataTransfer.getData('id'); //Obtengo el Id de la pieza por que me esta transfiriendo la data que sale con dragStart
//     // console.log('Pieza: '+id);
//     // console.log('Cajon:'+e.target.id); //Obtengo el id del cajon por que esta escuchando los eventos de los cajones
//     if( e.target.id === id ){
//         e.target.classList.remove('my_placeholder');
//         e.target.classList.add('my_placeholder_replaced');
//         e.target.classList.add('image_cover');
//         e.target.appendChild(document.getElementById(id));
//
//     }
// });
