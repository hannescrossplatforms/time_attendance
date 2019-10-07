<div class="form-group">
    <label>Category*</label>
    <select id="isplist" name="category_id" class="form-control" required>
    @foreach($data['categories'] as $category)
        <option value="{{ $category->id }}">
        {{ $category->name }}
        </option>
    @endforeach
    </select>
</div>