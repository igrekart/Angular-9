import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FourOhForComponent } from './four-oh-for.component';

describe('FourOhForComponent', () => {
  let component: FourOhForComponent;
  let fixture: ComponentFixture<FourOhForComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FourOhForComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FourOhForComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
