
document.addEventListener('DOMContentLoaded',function(event){
  var icon=document.querySelector('.fas')
   icon.addEventListener('click',function(event){
  
    if(icon.classList.contains('fa-angle-down')){
      
      icon.classList.remove('fa-angle-down')
      icon.classList.add('fa-angle-up')
      dt={
        sortmode:'ASC',
       
      }
    }
    else{
    
      icon.classList.remove('fa-angle-up')
      icon.classList.add('fa-angle-down')
      dt={
        sortmode:'DESC',
        
      }
    }
      $.ajax({
          url: '/handlers/tablehandler.php',
          type: 'POST',
      
          data:dt,
          dataType:'json',
          success: function (data) {
              document.querySelector('#tb').innerHTML=data
              
            },
          error:function(error) {
            console.log(error);
            }
          })
  })

  var searchArea=document.querySelector('#searchinput')
  searchArea.onkeyup=function(e){
    if (searchArea.value!="") {
      $.ajax({
          url: '/handlers/searchhandler.php',
          type: 'post',
          data: {
            val:searchArea.value
          },
          dataType:'json',
          success: function (data) {
              
              // document.querySelector('.pageNav').classList.add('hidden')
              document.querySelector('#tb').innerHTML=data
              
          },
          error:function(error){
            console.log(error)
          }
        });
    }
  }
})
 