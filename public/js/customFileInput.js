$('#csv_menu_file').on('change',function(){
    //get the file name
    var fileName = $(this).val();
    // delete 'C:\fakepath\'
    var cleanFileName = fileName.replace('C:\\fakepath\\', "");
    //replace the "Choose a file" label
    $(this).next('.custom-file-label').html(cleanFileName);
});