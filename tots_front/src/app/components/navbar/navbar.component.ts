import { Component, OnInit, HostListener } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router, RouterLink, RouterLinkActive } from '@angular/router';
import { AuthService } from '../../services/auth.service';
import { ButtonModule } from 'primeng/button';
import { RippleModule } from 'primeng/ripple';
import { MenuModule } from 'primeng/menu';
import { MenuItem } from 'primeng/api';

@Component({
  selector: 'app-navbar',
  standalone: true,
  imports: [CommonModule, RouterLink, RouterLinkActive, ButtonModule, RippleModule, MenuModule],
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.css']
})
export class NavbarComponent implements OnInit {
  isAdmin = false;
  isLoggedIn = false;
  isMobileMenuOpen = false;
  showUserMenu = false;
  userMenuItems: MenuItem[] = [];

  constructor(
    private authService: AuthService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.authService.currentUser$.subscribe(user => {
      this.isLoggedIn = !!user;
      this.isAdmin = user?.is_admin || false;
    });

    this.userMenuItems = [
      {
        label: 'Cerrar Sesión',
        icon: 'pi pi-sign-out',
        command: () => this.logout()
      }
    ];
  }

  logout(): void {
    this.authService.logout();
    this.isMobileMenuOpen = false;
    this.router.navigate(['/']);
  }

  getCurrentUser(): any {
    return this.authService.getCurrentUser();
  }

  toggleMobileMenu(): void {
    this.isMobileMenuOpen = !this.isMobileMenuOpen;
  }

  toggleUserMenu(): void {
    this.showUserMenu = !this.showUserMenu;
  }

  @HostListener('document:click', ['$event'])
  onDocumentClick(event: MouseEvent): void {
    const target = event.target as HTMLElement;
    const userTrigger = target.closest('.user-trigger');
    const userDropdown = target.closest('.user-dropdown');

    if (!userTrigger && !userDropdown && this.showUserMenu) {
      this.showUserMenu = false;
    }
  }
}
