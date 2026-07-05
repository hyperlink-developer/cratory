<x-layouts.public title="Contact Support">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6">Contact Support</h1>
            <p class="text-lg text-text-muted">Need help with something? We are here to answer your questions and assist you in any way we can.</p>
        </div>

        <div class="glass-card p-8 rounded-3xl border border-white/5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div>
                    <h3 class="text-xl font-bold text-white mb-4">Get in touch</h3>
                    <p class="text-text-muted mb-6">Fill out the form or reach out directly via email. Our support team typically responds within 24 hours during business days.</p>
                    
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 text-text-primary">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-text-muted">Email</p>
                                <a href="mailto:support@cratory.com" class="text-white hover:text-primary transition-colors">support@cratory.com</a>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4 text-text-primary">
                            <div class="w-10 h-10 rounded-full bg-accent/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-text-muted">Support Hours</p>
                                <p class="text-white">Mon-Fri, 9am - 5pm EST</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <form action="#" method="POST" class="space-y-4" onsubmit="event.preventDefault(); alert('Message sent! We will get back to you soon.');">
                        <div>
                            <label for="name" class="block text-sm font-medium text-text-secondary mb-1.5">Name</label>
                            <input type="text" id="name" name="name" class="input-field" placeholder="John Doe" required>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-text-secondary mb-1.5">Email address</label>
                            <input type="email" id="email" name="email" class="input-field" placeholder="john@example.com" required>
                        </div>
                        
                        <div>
                            <label for="message" class="block text-sm font-medium text-text-secondary mb-1.5">Message</label>
                            <textarea id="message" name="message" rows="4" class="input-field" placeholder="How can we help you?" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-full py-3 shadow-lg shadow-primary/20">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.public>
