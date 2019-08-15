<table class="table table-striped">
    <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Actions</th>
    </tr>

    <tr>
    @foreach ($data['checklistItems'] as $checklistItem)
    <tr>
        <td>{{ $checklistItem->title }}</td>
        <td>{{ $checklistItem->description }}</td>
        <td>
        <a href="bidvest_delete_checklist_item/<?php echo $checklistItem->id;?>" class="btn btn-default btn-sm">Delete checklist item</a>
        </td>
    </tr>
    @endforeach
    </tr>

</table>