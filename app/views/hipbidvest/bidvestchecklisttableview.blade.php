<table class="table table-striped">
<h3>Checklist</h3>
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

$(".table_view_item").on('click', function(){

    $listRoomID = $("#list_room_id").val();
    $itemID = $(this).attr("itemID");

    $.ajax({
        url: pathname + 'hipbidvest/bidvest_delete_assigned_checklist_item',
            type: 'post',
            dataType: 'html',
            data: {
                'room_id': $listRoomID,
                'item_id': $itemID
            },
            success: function(result) {
                $("#table-container").html(result);

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {

            }
        });

});
</script>