$(document).ready(function($) {
    $(".table-row").click(function() {
        window.document.location = $(this).data("href");
    });
});

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});



// download things in tables
$(document).ready(function() {
    $(".table-row-download").click(function(e) {
        e.preventDefault();
        var partID = $(this).data("href");
        //alert(partID);
        $.ajax({
            type: "GET",
            url: "/php/DownloadPart.php",
            data: {
                id: partID
            },
            success: function(data) {
                //alert(data);
                document.getElementById('my_iframe').src = data;

            }
        });
        e.preventDefault();
        //return false;
    });
});

$(document).ready(function() {
    $("#ProjectNotes").height($("textarea")[0].scrollHeight);
});


var i = -1;

function duplicate() {
    $("#tempLabel").hide();
    if (i == -1) {
        i++;
        document.getElementById("uploadtable").style.display = 'inline';

    } else {
        i++;
        var oldRow = $("#row0");
        var newrow = oldRow.clone().attr('id', 'row' + i);
        (newrow).find('td').children().each(function() {
            var str = $(this).attr("id").slice(0, -1);
            $(this).attr('id', str + i).attr('name', str + i);
            if ($(this).attr("type") == "file") {
                $(this).val("");
                //$(this).data = "";
            }
            //alert(d);
        });
        //$('.test > tbody:last-child').append($newrow);
        $(oldRow).parent().append(newrow);
        //$(id).appendTo(".test");
    }

}

function remove(ID) {
    var index = ID.substr(ID.length - 1);
    //alert(index);
    if (index != 0) {
        document.getElementById("row" + index).remove();
    }

}

var index = 0;

function setIndex(setIndex) {
    //alert(index);
    index = setIndex;
    //var grossOldElement = document.getElementById(oldID);
    //var shinyNewElement = $(grossOldElement).clone().attr('id',newID);
    //$(grossOldElement).replaceWith(shinyNewElement);

}

// ajax request to add menubar to top of page, allows menubar implementation to be centralized to one php script
$(document).ready(function() {
    $.ajax({
        type: "POST",
        url: "/php/Menu.php",
        success: function(data) {
            document.getElementById('Nav').innerHTML = data;
        }
    });
});

// build content adjusting modal
$(document).ready(function($) {

    $("#contents").click(function() {

        var stlArray = [];
        var slicedFilesArray = [];
        var i = 0;
        var contentTable;
        $("#uploadtable").find('input[type="file"]').each(function() {
            var filename = $("#stlupload" + i).val().split('\\').pop();
            slicedFilesArray.push(filename);
            i++;
        });
        $('#partsTable tr td:nth-child(2)').each(function() {
            var filename = $(this).html();
            stlArray.push(filename);
        });
        contentTable = '<table cellspacing="10" id="poop" class="table-condensed table-hover"><thead><tr><th><strong>Sliced Parts</strong></th><th><strong>stls</strong></th><th><strong>Quantities</strong></th></tr></thead><tbody>';

        $.each(slicedFilesArray, function(index, value) {
            contentTable += '<tr><td>' + value + '</td><td>' + stlArray[0] + '</td><td><input align="center" type="number" min="0" name="quantityupdown0" id="quantityupdown0" value="0" onkeydown="return false"/></td></tr>';
            $.each(stlArray, function(index, value) {
                if (index != 0)
                    contentTable += '<tr><td></td><td>' + value + '</td><td><input align="center" type="number" min="0" name="quantityupdown0" id="quantityupdown0" value="0" onkeydown="return false"/></td></tr>';
            });
        });
        contentTable += '</tbody></table>';
        document.getElementById('modal-body-contents').innerHTML = contentTable;
    });
});


//save contents of each sliced file to individual textareas
$(document).ready(function($) {

    $("#modalSave").click(function() {
        var index = 0;
        var i = 0;
        var myTableArray = [];
        $("#poop tr:not(:first)").each(function() {

            if ($(this).find('td:nth-child(1)').text() != "" && i != 0) {
                $("#contentstext" + index).text(myTableArray);
                //alert(myTableArray);
                index++;
                i = 0;
                myTableArray = [];
            }
            var quantity = $(this).find('input').val();
            var partName = $(this).find('td:nth-child(2)').text();
            if (quantity != "0") {
                myTableArray.push(quantity + 'x ' + partName);
            }
            i++;
        });
        $("#contentstext" + index).text(myTableArray);
        //alert(myTableArray);
    });
});

// build project editing modal
$(document).ready(function($) {

    $(".editproject").click(function() {
        var infoArray = [];
        var partsArray = [];

        // load project info
        $(this).siblings("#infoTable").find("tr").each(function() {
            var arrayOfThisRow = [];
            var tableData = $(this).find('td');
            if (tableData.length > 0) {
                tableData.each(function() {
                    arrayOfThisRow.push($(this).text());
                });
                infoArray.push(arrayOfThisRow);
            }
        });

        // load project parts
        $(this).siblings("#partsTable").find("tr").each(function() {
            var arrayOfThisRow = [];
            var tableData = $(this).find('td');
            if (tableData.length > 0) {
                tableData.each(function() {
                    arrayOfThisRow.push($(this).text());
                });
                partsArray.push(arrayOfThisRow);
            }
        });


        //alert(infoArray[1][1]);
        // fill in all the stuff in modal with previous values
        document.getElementById("nameofproject").value = infoArray[1][0];
        document.getElementById("completiondate").value = infoArray[1][5].split(' ').join('T'); // format doesnt work for date box without T
        if (infoArray[1][2] != "") {
          $('input:radio[name=units][value='+infoArray[1][2]+']').attr('checked', true);
        }
        if (infoArray[1][1] != "") {
          $("input:radio[name='type'][value='"+infoArray[1][1]+"']").attr('checked', true);
        }

        $('#currentpartstable tr:not(:last) td #operation').each(function(){
          if($(this).val() != "None"){
            $(this).closest('tr').remove();
          };
        });

        $("#files").empty();
        for(i=1;partsArray.length > i;i++){
          var option = document.createElement("option");
          option.text = partsArray[i][1];
          document.getElementById("files").add(option);
        }

        // put the project ID in the special box
        $("#projectID").val($(this).siblings("form").find("#text").val());
        //alert($("#projectID").val());

    });
});

// save project editing modal
$(document).ready(function($) {

    $(".saveproject").click(function() {
      //$("#myModal").find("*:hidden").remove();

      var partsArray = [];
      var i = 0;

      // alter tags of add rows to match format of php script
      $("#currentpartstable").find("tr").each(function() {
          var rowType = $(this).find('#operation');
          if (rowType.val() == "Add") {
              $(this).find('td').children().each(function() {
                  var str = $(this).attr("id");
                  $(this).attr("id", str+i).attr('name', str+i);
                  console.log($(this).attr("id"));
              });
              i++;
          }
      });

      // save edit rows of the parts table
      $("#currentpartstable").find("tr").each(function() {
          var arrayOfThisRow = [];
          var rowType = $(this).find('#operation');
          if (rowType.val() == "Edit") {
            $(this).find('td').children().each(function() {
              if($(this).is(":visible")){
                arrayOfThisRow.push($(this).val());
              }
              });
              partsArray.push(arrayOfThisRow);
          }
      });
      $('<input/>').attr({ type: 'text', id: 'partsArray', name: 'partsArray' }).val(JSON.stringify(partsArray)).appendTo('#editform');
      //alert($("#partsArray").val());



    });
});

// establish project editing modal file edit behavior
$(document).on('change',"#operation", function() {
    index = $(this).val();
     if(index == "None"){
       $(this).closest('tr').find("td").children(":not(#operation)").each(function(){$(this).css("display", "none")});
     }
     else if(index == "Edit"){
      $('#currentpartstable').append($('#currentpartstable tr:last').clone());
      $(this).closest('tr').find("td #files").show();
      $(this).closest('tr').find("td #edit").show();
      $(this).closest('tr').find("td #material").show();
      editIndex = $(this).closest('tr').find("td #edit").val();


     }
     else if(index == "Add"){
       $('#currentpartstable').append($('#currentpartstable tr:last').clone());
       $(this).find("option").each(function() {
         if ($(this).val() == "Edit")
             $(this).remove();
           });
           $(this).closest('tr').find("td:not(:first)").remove();
       $(this).closest('tr').append($('#uploadtable tr').find("td").clone()).css("display", "block");
     }
});


// ensure only one none is there
$(document).on('change',"#operation", function() {
    $('#currentpartstable tr:not(:last) td #operation').each(function(){
      if($(this).val() == "None"){
        $(this).closest('tr').remove();
      };
    })
});

// ensure only one the correct html element is supplied with the proper action
$(document).on('change',"#edit", function() {
  index = $(this).val();

  $(this).closest('tr').find("td #stlupload").hide();
  $(this).closest('tr').find("td #material").hide();
  $(this).closest('tr').find("td #quantityupdown").hide();
  $(this).closest('tr').find("td #notes").hide();

      //if(index == "Replace"){
      //  $(this).closest('tr').find("td #stlupload").css("display", "inline");
      //}
      if(index == "Material/Color"){
        $(this).closest('tr').find("td #material").show();
      }
      else if(index == "Quantity"){
        $(this).closest('tr').find("td #quantityupdown").show();
      }
      else if(index == "Part Notes"){
        $(this).closest('tr').find("td #notes").show();
      }
});


// jerry rigged piece of crap js to update an invisible text field containing the index of selected status
function update() {
    var index = $("select[name='statusDropdown'] option:selected").index();
    document.getElementById("realStatus").value = index + 1;

    }

// status equals paid
$(document).on('change',"#statusDropdown", function() {
  if($(this).val() == "Paid"){
    $("#newNote").attr("placeholder", "Type the amount paid into here in the following formats: $xx.xx, $x.xx etc");
  }
    else{
      $("#newNote").attr("placeholder", "Type your note in here");
    }
    if($(this).val() == "History"){
      alert("Changing the status to history deletes all user supplied files");
    }
  });

  // ensure only one the correct html element is supplied with the proper action
  $(document).on('change',"#AdminRadio", function() {
    index = $(this).val();

    if(index == "Admin"){
      $(this).closest('tr').find("td #UserTypeInput").show();
    }
    else if(index == "NON-Admin"){
      $(this).closest('tr').find("td #UserTypeInput").show();
    }
    else if(index == "None"){
      $(this).closest('tr').find("td #UserTypeInput").hide();
    }
    });

    $(document).on('change',"#MaterialRadio", function() {
      index = $(this).val();

      if(index == "Add"){
        $(this).closest('tr').find("td #MaterialInput").show();
        $(this).closest('tr').find("td #MaterialSelect").hide();
      }
      else if(index == "Delete"){
        $(this).closest('tr').find("td #MaterialSelect").show();
        $(this).closest('tr').find("td #MaterialInput").hide();
      }
      else if(index == "None"){
        $(this).closest('tr').find("td #MaterialSelect").hide();
        $(this).closest('tr').find("td #MaterialInput").hide();
      }

  });

// function newLS($name,$data)
// {
//         // Check browser support
//   if (typeof(Storage) !== "undefined") {
//       // Store
//       document.localStorage.setItem($name,$data);
//   } else {
//       alert("Sorry, your browser does not support Web Storage which is needed to use this site. Please update or change browers in order to continue");
//       header("Refresh:0; url=loginpage.html");
//   }
// }
