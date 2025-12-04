<x-layouts.app>
    <div style="padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1>Book Management</h1>
            <a href="{{ route('admin.book.create') }}" style="background: #3498DB; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">
                + Add New Book
            </a>
        </div>

        @if(session('success'))
            <div style="background: #D4EDDA; border: 1px solid #C3E6CB; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #F8D7DA; border: 1px solid #F5C6CB; color: #721C24; padding: 12px; border-radius: 5px; margin-bottom: 20px;">
                {{ session('error') }}
            </div>
        @endif

        @if($books->count() > 0)
            <div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #F8F9FA; border-bottom: 2px solid #DEE2E6;">
                            <th style="padding: 15px; text-align: left; font-weight: 600;">Title</th>
                            <th style="padding: 15px; text-align: left; font-weight: 600;">Author</th>
                            <th style="padding: 15px; text-align: left; font-weight: 600;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $book)
                            <tr style="border-bottom: 1px solid #DEE2E6;">
                                <td style="padding: 15px;">
                                    <a href="{{ route('admin.book.show', $book->id) }}" style="color: #3498DB; text-decoration: none; font-weight: 500;">
                                        {{ $book->title }}
                                    </a>
                                </td>
                                <td style="padding: 15px;">
                                    {{ $book->author }}
                                </td>
                                <td style="padding: 15px;">
                                    <a href="{{ route('admin.book.edit', $book->id) }}" style="background: #F39C12; color: white; padding: 6px 12px; border-radius: 3px; text-decoration: none; font-size: 12px; margin-right: 5px; display: inline-block;">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.book.destroy', $book->id) }}" style="display: inline-block;" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: #E74C3C; color: white; padding: 6px 12px; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 20px;">
                {{ $books->links() }}
            </div>
        @else
            <div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 40px; text-align: center;">
                <p style="color: #95A5A6; font-size: 16px; margin-bottom: 20px;">No books found</p>
                <a href="{{ route('admin.book.create') }}" style="background: #3498DB; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block;">
                    Create First Book
                </a>
            </div>
        @endif
    </div>
</x-layouts.app>
