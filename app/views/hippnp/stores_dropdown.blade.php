<div class="col-md-4" style="width:30%;">
    <div class="col-md-4" style="width:43%; padding:6px 0px 0px 0px;">
        <label>Store</label>
    </div>
    <div class="col-md-4" style="width:57%;padding:0px 0px 0px 0px;">
        <select id="brandstore" onchange="get_categories_for_store()" class="form-control"
            name="brandstore">
            <option value="">Select</option>
            @foreach($data['all_stores_for_province'] as $store)
            <option value="{{ $store->id }}">
            {{ $store->sitename }}
            </option>
            @endforeach
        </select>
    </div>
</div>

<script>

    function get_categories_for_store() {

        var storeID = $("#brandstore").val();

        $.ajax({
            url: pathname + 'hippnp/getCategoriesForStore/' + storeID,
            type: 'get',
            dataType: 'html',
            success: function(result) {
                $("#categories-select-container").html(result);

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("error");
            }
        });


    }

</script>