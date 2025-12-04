<x-layouts.app>
    <div style="padding: 20px;">
        <div style="margin-bottom: 20px;">
            <a href="{{ route('admin.book.index') }}" style="color: #3498DB; text-decoration: none;">← Back to Books</a>
        </div>

        <div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; max-width: 600px;">
            <h1 style="margin-bottom: 20px;">{{ $book->title }}</h1>

            <div style="margin-bottom: 20px;">
                <h3 style="color: #95A5A6; font-size: 14px; margin-bottom: 5px;">Author</h3>
                <p style="font-size: 16px; margin: 0;">{{ $book->author }}</p>
            </div>

            @if($book->description)
                <div style="margin-bottom: 20px;">
                    <h3 style="color: #95A5A6; font-size: 14px; margin-bottom: 5px;">Description</h3>
                    <p style="font-size: 14px; line-height: 1.6; margin: 0;">{{ $book->description }}</p>
                </div>
            @endif

            <div style="display: flex; gap: 10px;">
                <a href="{{ route('admin.book.edit', $book->id) }}" style="background: #F39C12; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block; font-weight: 600;">
                    Edit
                </a>
                <form method="POST" action="{{ route('admin.book.destroy', $book->id) }}" style="display: inline-block;" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background: #E74C3C; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: 600;">
                        Delete
                    </button>
                </form>
                <a href="{{ route('admin.book.index') }}" style="background: #95A5A6; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block; font-weight: 600;">
                    Back
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
