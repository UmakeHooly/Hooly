import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class FoodtruckService {

  private baseUrl = 'http://localhost:8000';

  constructor(private http: HttpClient) { }

  getAllFoodtrucks(): Observable<any> {
    return this.http.get(`${this.baseUrl}/foodtrucks`);
  }

  addReservation(formData:FormData): Observable<any> {
    return this.http.post(`${this.baseUrl}/reservation/add`, formData);
  }
}
