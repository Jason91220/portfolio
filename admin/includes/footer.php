    </main>
    
    <!-- Modal de confirmation -->
    <div class="modal-backdrop" id="confirmModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3>Confirmation</h3>
                <button type="button" class="modal-close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="confirmMessage">Êtes-vous sûr de vouloir effectuer cette action ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmAction">Confirmer</button>
            </div>
        </div>
    </div>
    
    <script>
        // Vérifier si le mode sombre est activé dans le localStorage
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion du mode sombre
            const darkModeEnabled = localStorage.getItem('darkMode') === 'enabled';
            if (darkModeEnabled) {
                document.body.classList.remove('light-mode');
                document.body.classList.add('dark-mode');
            }
            
            // Gestion du menu mobile
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const adminSidebar = document.querySelector('.admin-sidebar');
            const adminContent = document.querySelector('.admin-content');
            
            // Vérifier si le menu était ouvert précédemment
            const sidebarOpen = localStorage.getItem('adminSidebarOpen') === 'true';
            if (sidebarOpen && window.innerWidth > 768) {
                adminSidebar.classList.add('show');
            }
            
            // Fonction pour basculer l'état du menu
            function toggleSidebar() {
                adminSidebar.classList.toggle('show');
                
                // Enregistrer l'état du menu dans le localStorage
                localStorage.setItem('adminSidebarOpen', adminSidebar.classList.contains('show'));
            }
            
            // Ajouter l'événement au bouton de menu mobile
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', toggleSidebar);
            }
            
            // Fermer le menu en cliquant en dehors sur mobile
            document.addEventListener('click', function(event) {
                const isMobile = window.innerWidth <= 768;
                const isMenuOpen = adminSidebar.classList.contains('show');
                const clickedOutsideSidebar = !adminSidebar.contains(event.target) && event.target !== mobileMenuToggle;
                
                if (isMobile && isMenuOpen && clickedOutsideSidebar) {
                    adminSidebar.classList.remove('show');
                    localStorage.setItem('adminSidebarOpen', 'false');
                }
            });
            
            // Fermer le menu lors du redimensionnement de la fenêtre
            window.addEventListener('resize', function() {
                if (window.innerWidth <= 768) {
                    adminSidebar.classList.remove('show');
                }
            });
            
            // Modal de confirmation
            const modal = document.getElementById('confirmModal');
            const closeButtons = document.querySelectorAll('[data-dismiss="modal"]');
            const confirmButton = document.getElementById('confirmAction');
            
            // Fermer la modal
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    modal.classList.remove('show');
                });
            });
            
            // Fermer la modal en cliquant en dehors
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.classList.remove('show');
                }
            });
            
            // Fonction pour ouvrir la modal de confirmation
            window.confirmAction = function(message, callback) {
                const confirmMessage = document.getElementById('confirmMessage');
                confirmMessage.textContent = message;
                
                // Définir l'action de confirmation
                confirmButton.onclick = function() {
                    callback();
                    modal.classList.remove('show');
                };
                
                // Afficher la modal
                modal.classList.add('show');
            };
            
            // Actualisation en temps réel des statistiques
            function updateStats() {
                const statsElements = document.querySelectorAll('[data-stat]');
                
                if (statsElements.length > 0) {
                    fetch('ajax/get_stats.php')
                        .then(response => response.json())
                        .then(data => {
                            statsElements.forEach(element => {
                                const statName = element.getAttribute('data-stat');
                                if (data[statName] !== undefined) {
                                    element.textContent = data[statName];
                                }
                            });
                        })
                        .catch(error => console.error('Erreur lors de la mise à jour des statistiques:', error));
                }
            }
            
            // Mettre à jour les statistiques toutes les 30 secondes
            if (document.querySelectorAll('[data-stat]').length > 0) {
                setInterval(updateStats, 30000);
            }
        });
    </script>
</body>
</html>
