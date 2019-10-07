<div class="form-group">
    <label>Category*</label>
    <select id="isplist" name="category_id" class="form-control" required>
    @foreach($data['all_categories_for_filter'] as $category)
        <option value="{{ $category->id }}">
        {{ $category->name }}
        </option>
    @endforeach
    </select>
</div>