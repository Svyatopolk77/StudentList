var page=1
  function ajaxreq(dt){
    $.ajax({
          url: '/handlers/pagehandler.php',
          type: 'POST',
      
          data:dt,
          dataType:'json',
          success: function (data) {
            if (data=="") {
              Swal.fire({
                text: 'Больше страниц нет',
                target: '#custom-target',
                customClass: {
                  container: 'position-absolute'
                },
                toast: true,
                position: 'bottom-right',
                timer:1600
              })
              page-=1
            }else{
            document.querySelector('#tb').innerHTML=data
          }

              
            },
          error:function(error) {
            console.log(error);
          }
          })
  }

  function prevPage(){
    page=page>1?page-1:1
      dt={
        page:page,
      }  
      ajaxreq(dt)
  }
  function nextPage(){

      page+=1
      dt={
    
        page:page,
      }

      ajaxreq(dt)
      
  }

