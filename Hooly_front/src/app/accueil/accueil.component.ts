import { Component, OnInit } from '@angular/core';
import { FoodtruckService } from '../services/foodtruck/foodtruck.service';
import { tap } from 'rxjs/operators';
import { Router } from '@angular/router';

@Component({
  selector: 'app-accueil',
  templateUrl: './accueil.component.html',
  styleUrls: ['./accueil.component.css']
})
export class AccueilComponent implements OnInit {

  foodtrucks: any = [];
  selectedFoodtruck:any=null;
  isDateValid: boolean = false;
  reservationDate:any=null;
  reservationForm:boolean=false;
  dateText:any='Veuillez sélectionner une date dans le futur.'
  response:any=null;

  constructor(private foodtruckService: FoodtruckService,private router: Router) { }

  ngOnInit(): void {
    this.loadFoodtrucks();
  }

  loadFoodtrucks(): void {
    this.foodtruckService.getAllFoodtrucks().subscribe({
      next: (foodtrucks) => {
        this.foodtrucks = foodtrucks;
      },
      error: (error) => {
        console.error('Error loading foodtrucks:', error);
      },
      
    });
  }

  checkDate(value: string): void {
    const selectedDate = new Date(value);
    const today = new Date();
    this.isDateValid = selectedDate > today;
  }

  onSubmit(): void {
    if (!this.isDateValid) {
      alert('Veuillez sélectionner une date dans le futur.');
      return;
    }
    else{
      const formData = new FormData();
      formData.append('date', this.reservationDate);
      formData.append('foodtruck', this.selectedFoodtruck);
      this.foodtruckService.addReservation(formData).subscribe({
        next: (response) => {
          this.response = response;
        },
        error: (error) => {
          if (error.error instanceof ErrorEvent) {
            // Une erreur côté client ou réseau s'est produite. Traiter ici.
            this.response = `Erreur côté client: ${error.error.message}`;
          } else {
            // Le backend a retourné une réponse d'échec.
            // Le serveur peut retourner des réponses d'échec qui sont traitées ici par le bloc `error`.
            this.response = error.error ;
          }
        },
        
      });
    }
  }

}
