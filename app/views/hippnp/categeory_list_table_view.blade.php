<div class="table-responsive">
    <table class="table table-striped">
    <tr>
        <th>Category Name</th>
        <th>Actions</th>
    </tr>
    @foreach ($data['engageCategories'] as $category)
    <tr>
        <td>{{ $category->name }}</td>
        <td>
        <a class="btn btn-default btn-sm" href="{{route('hippnp_remove_category', ['id' => $category->id])}}">Remove</a>
        </td>
    </tr>
    @endforeach

    </table>
</div>