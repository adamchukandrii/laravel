<tr id="file-{{ $file->id }}">
    <td>{{ $file->name }}</td>
    <td>{{ $file->size }}</td>
    <td>
        <a href="{{ route('file.delete', $file->id) }}" class="btn btn-danger btn-sm">Delete</a>
    </td>
</tr>
