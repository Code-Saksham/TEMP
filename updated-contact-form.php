<!-- Contact Section (Replace this in your index.php) -->
<section id="contact" class="h-screen flex flex-col justify-center items-center text-white">
    <h2 class="text-4xl font-bold">📞 Contact Us</h2>
    
    <div id="contactFormContainer" class="w-full sm:w-2/3 md:w-1/2 lg:w-1/3 bg-white p-6 rounded shadow-lg">
        <div id="contactResponse" class="hidden mb-4 p-3 rounded"></div>
        
        <form id="contactForm" class="space-y-4">
            <div>
                <label class="block text-gray-700 font-medium mb-1">Full Name</label>
                <input type="text" id="full_name" name="full_name" placeholder="Your Name" class="w-full mb-2 p-2 border rounded text-gray-700" required>
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-1">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Your Email" class="w-full mb-2 p-2 border rounded text-gray-700" required>
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-1">Message</label>
                <textarea id="message" name="message" placeholder="Your Message" class="w-full mb-2 p-2 border rounded text-gray-700" rows="5" required></textarea>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Send Message</button>
        </form>
    </div>
    
    <script>
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const fullName = document.getElementById('full_name').value;
            const email = document.getElementById('email').value;
            const message = document.getElementById('message').value;
            const responseDiv = document.getElementById('contactResponse');
            
            // Send AJAX request
            fetch('process_contact.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `full_name=${encodeURIComponent(fullName)}&email=${encodeURIComponent(email)}&message=${encodeURIComponent(message)}`
            })
            .then(response => response.json())
            .then(data => {
                // Show response message
                responseDiv.textContent = data.message;
                responseDiv.classList.remove('hidden', 'bg-red-100', 'text-red-700', 'bg-green-100', 'text-green-700');
                
                if (data.success) {
                    responseDiv.classList.add('bg-green-100', 'text-green-700');
                    document.getElementById('contactForm').reset();
                } else {
                    responseDiv.classList.add('bg-red-100', 'text-red-700');
                }
            })
            .catch(error => {
                responseDiv.textContent = 'An error occurred. Please try again later.';
                responseDiv.classList.remove('hidden');
                responseDiv.classList.add('bg-red-100', 'text-red-700');
            });
        });
    </script>
</section>
