<table class="table table-striped">
    <input type="hidden" id="list_room_id" name="" value={{$data['room_id']}}>
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
        <a itemID="<?php echo $checklistItem->id;?>" class="btn btn-default btn-sm table_view_item">Delete checklist item</a>
        </td>
    </tr>
    @endforeach
    </tr>

</table>

<script>

$(".table_view_item").on('click', function(e){

    debugger;
    $listRoomID = $("#list_room_id").val();
    alert($listRoomID);
    $itemID = $(e).prop("itemID");
    alert($itemID);


});
</script>