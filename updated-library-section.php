<!-- Library Section (Replace this in your index.php) -->
<section id="library" class="min-h-screen p-8 bg-gray-50">
    <h2 class="text-4xl font-bold text-center mb-6">📚 Explore Our Library</h2>
    
    <div class="flex flex-col md:flex-row md:space-x-4 mb-6">
        <input type="text" id="searchInput" placeholder="🔍 Search for books by title or author..." 
               class="w-full md:w-2/3 p-4 mb-4 md:mb-0 border rounded shadow focus:outline-none" />
        
        <select id="genreFilter" class="w-full md:w-1/3 p-4 border rounded shadow focus:outline-none">
            <option value="">All Genres</option>
            <option value="Fiction">Fiction</option>
            <option value="Science">Science</option>
            <option value="Fantasy">Fantasy</option>
            <option value="Non-Fiction">Non-Fiction</option>
        </select>
    </div>

    <!-- Loading indicator -->
    <div id="loadingBooks" class="text-center py-10 hidden">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        <p class="mt-2">Loading books...</p>
    </div>

    <!-- Book List -->
    <div id="bookList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Books will be loaded here via JavaScript -->
    </div>
    
    <div id="noBooks" class="text-center py-10 hidden">
        <p class="text-gray-600">No books found matching your search criteria.</p>
    </div>

    <script>
        // Function to load books from database
        function loadBooks() {
            const searchInput = document.getElementById('searchInput').value;
            const genreFilter = document.getElementById('genreFilter').value;
            const bookList = document.getElementById('bookList');
            const noBooks = document.getElementById('noBooks');
            const loadingBooks = document.getElementById('loadingBooks');
            
            // Show loading indicator
            bookList.innerHTML = '';
            noBooks.classList.add('hidden');
            loadingBooks.classList.remove('hidden');
            
            // Fetch books with filters
            fetch(`get_books.php?search=${encodeURIComponent(searchInput)}&genre=${encodeURIComponent(genreFilter)}`)
                .then(response => response.json())
                .then(books => {
                    loadingBooks.classList.add('hidden');
                    
                    if (books.length === 0) {
                        noBooks.classList.remove('hidden');
                        return;
                    }
                    
                    // Create book cards
                    books.forEach(book => {
                        const bookCard = document.createElement('div');
                        bookCard.className = 'book-card p-4 bg-white shadow rounded hover:shadow-md transition-shadow';
                        bookCard.setAttribute('data-genre', book.genre);
                        
                        bookCard.innerHTML = `
                            <h3 class="text-2xl font-bold">${book.title}</h3>
                            <p class="text-gray-600">by ${book.author}</p>
                            <p class="mb-2">📖 Genre: ${book.genre}</p>
                            <p class="text-sm text-gray-500">Published: ${book.published_year}</p>
                            ${book.description ? `<p class="mt-2 text-gray-700">${book.description.substring(0, 100)}${book.description.length > 100 ? '...' : ''}</p>` : ''}
                        `;
                        
                        bookList.appendChild(bookCard);
                    });
                })
                .catch(error => {
                    loadingBooks.classList.add('hidden');
                    console.error('Error loading books:', error);
                    
                    // Show error message
                    bookList.innerHTML = '<div class="col-span-full text-center text-red-600">Error loading books. Please try again later.</div>';
                });
        }
        
        // Initial load
        document.addEventListener('DOMContentLoaded', loadBooks);
        
        // Search and filter
        document.getElementById('searchInput').addEventListener('input', loadBooks);
        document.getElementById('genreFilter').addEventListener('change', loadBooks);
    </script>
</section>
