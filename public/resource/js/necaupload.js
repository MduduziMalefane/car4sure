
// ************************ Drag and drop ***************** //
function NecaChecksum(s)
{
    var chk = 0x12345678;
    var len = s.length;
    for (var i = 0; i < len; i++)
    {
        chk += (s.charCodeAt(i) * (i + 1));
    }

    return (chk & 0xffffffff).toString(16);
}
function initNecaDrop(settings)// DropZone,url)
{
    var maxUploadLimit = 0;
  

    var url = settings.url != null ? settings.url : "http://127.0.0.1";

    let dropArea = document.getElementById(settings.DropZone);
    if (dropArea == null)
    {
        return;
    }

    if (settings.MaxFiles != null && settings.MaxFiles > 0)
    {
        maxUploadLimit = settings.MaxFiles;
    }

// Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });
    // Highlight drop area when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        if (settings.OnDragOver != null)
        {
            dropArea.addEventListener(eventName, settings.OnDragOver, false);
        }
    });
    ['dragleave', 'drop'].forEach(eventName => {
        if (settings.OnDragLeave != null)
        {
            dropArea.addEventListener(eventName, settings.OnDragLeave, false);
        }
    });
    // Handle dropped files
    dropArea.addEventListener('drop', handleDrop, false);
    function preventDefaults(e)
    {
        e.preventDefault();
        e.stopPropagation();
    }

    function handleDrop(e)
    {
        if (e == null || e.dataTransfer == null || e.dataTransfer.files == null || e.dataTransfer.files.length == 0)
        {
            if (settings.OnCriticalError != null)
            {
                settings.OnCriticalError("Files have not been dropped");
            }
            
            if (settings.OnEndUpload != null)
            {
                settings.OnEndUpload();
            }
        }
        else
        {
            var dt = e.dataTransfer;
            var files = dt.files;
            handleFiles(files);
        }
    }

    function handleFiles(files)
    {
        files = [...files];



        var maxUploadFiles = files.length;
        if (maxUploadLimit != 0 && maxUploadLimit < files.length)
        {
            maxUploadFiles = maxUploadLimit;
        }
        
        if (settings.OnBeginUpload != null)
        {
            settings.OnBeginUpload(files);
        }
        
        for (var i = 0; i < maxUploadFiles; i++)
        {
            var file = files[i];
            file.id = NecaChecksum(file.name) + (new Date().getTime()).toString();
            
            if(settings.OnFileAdd!=null)
            {
                settings.OnFileAdd(file);
            }
            uploadFile(file);
        }

        if (settings.OnEndUpload != null)
        {
            settings.OnEndUpload();
        }

    }


    function uploadFile(file)
    {
      
        var xhr = new XMLHttpRequest();
        var formData = new FormData();
        xhr.open('POST', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        // Update progress (can be used to show progress indicator)
        xhr.upload.addEventListener("progress", function (e)
        {
            //updateProgress(i, (e.loaded * 100.0 / e.total) || 100);
            if (settings.OnUploadProgress != null)
            {
                settings.OnUploadProgress(file, e);
            }
        });
        xhr.addEventListener('readystatechange', function (e)
        {
            if (xhr.readyState == 4 && xhr.status == 200)
            {
                // updateProgress(i, 100); // <- Add this
                if (settings.OnUploadComplete != null)
                {
                    settings.OnUploadComplete(file, xhr.responseText);
                }
            }
            else if (xhr.readyState == 4 && xhr.status != 200)
            {
                if (settings.OnUploadError != null)
                {
                    settings.OnUploadError(file, xhr.responseText);
                }

            }


        });
        //formData.append('upload_preset', 'ujpu6gyk');
        formData.append('file', file);
        //xhr.send(formData);
    }
}


