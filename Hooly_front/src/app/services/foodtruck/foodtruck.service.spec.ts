import { TestBed } from '@angular/core/testing';

import { FoodtruckService } from './foodtruck.service';

describe('FoodtruckService', () => {
  let service: FoodtruckService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(FoodtruckService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
