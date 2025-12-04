<x-layouts.app>
    <div style="padding: 20px;">
        <div style="margin-bottom: 20px;">
            <h1>Edit Book</h1>
        </div>

        <div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; max-width: 600px;">
            <form method="POST" action="{{ route('admin.book.update', $book->id) }}">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Title <span style="color: red;">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $book->title) }}" required style="width: 100%; padding: 10px; border: 1px solid #DEE2E6; border-radius: 5px; font-size: 14px; box-sizing: border-box;">
                    @error('title')
                        <span style="color: #E74C3C; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Author <span style="color: red;">*</span></label>
                    <input type="text" name="author" value="{{ old('author', $book->author) }}" required style="width: 100%; padding: 10px; border: 1px solid #DEE2E6; border-radius: 5px; font-size: 14px; box-sizing: border-box;">
                    @error('author')
                        <span style="color: #E74C3C; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Description</label>
                    <textarea name="description" style="width: 100%; padding: 10px; border: 1px solid #DEE2E6; border-radius: 5px; font-size: 14px; min-height: 120px; box-sizing: border-box;">{{ old('description', $book->description) }}</textarea>
                    @error('description')
                        <span style="color: #E74C3C; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" style="background: #27AE60; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: 600;">
                        Update Book
                    </button>
                    <a href="{{ route('admin.book.index') }}" style="background: #95A5A6; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block; font-weight: 600;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
