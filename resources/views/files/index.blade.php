<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Files</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body>

<div class="container mt-5">
    <h1>Files</h1>
    <form id="uploadForm" enctype="multipart/form-data" class="mb-4">
        @csrf
        <div class="form-group">
            <label for="file">Select File to Upload (PDF or DOCX, max 10MB)</label>
            <input type="file" name="file" id="file" accept=".pdf, .docx" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Upload File</button>
    </form>

    <div id="uploadStatus"></div>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>File Name</th>
            <th>Size (bytes)</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody id="fileList">
        @foreach($files as $file)
            @include('files.file', $file)
        @endforeach
        </tbody>
    </table>
</div>
<script type="module">
    $(document).ready(() => {
        $('#uploadForm').submit(function (e) {
            e.preventDefault();
            if ($('#file')[0].files.length === 0) {
                $('#uploadStatus').html('<div class="alert alert-warning">Please select a file before submitting.</div>');
                return;
            }

            const formData = new FormData(this);
            $.ajax({
                url: '/upload',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $('#uploadStatus').html('<div class="alert alert-success">File uploaded successfully!</div>');

                    const newFileRow = `
                    <tr id="file-${response.file.id}">
                        <td>${response.file.name}</td>
                        <td>${response.file.size}</td>
                        <td>
                            <a href="/file/delete/${response.file.id}" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                `;
                    $('#fileList').append(newFileRow);
                    $('#file').val('');
                },
                error: function (error) {
                    $('#uploadStatus').html('<div class="alert alert-danger">' + error.responseJSON.message + '</div>');
                }
            });
        })
    });
</script>
</body>
</html>
