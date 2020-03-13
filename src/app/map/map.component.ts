import { Component, OnInit, Input } from '@angular/core';
import * as L from 'leaflet';

@Component({
  selector: 'app-map',
  templateUrl: './map.component.html',
  styleUrls: ['./map.component.css']
})
export class MapComponent implements OnInit {

  private map;
  @Input() lat;
  @Input() long;

  constructor() { }

  ngOnInit(): void {
  }

  ngAfterViewInit(): void {
    this.initMap();
    const tiles = L.tileLayer( 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    });
    var circle = L.circle([this.long, this.lat], {
                      color: 'red',
                      fillColor: '#f03',
                      fillOpacity: 0.5,
                      radius: 500
                  }).addTo(this.map);
    tiles.addTo(this.map);
  }

  private initMap(): void {
    this.map = L.map('map').setView([this.long,this.lat], 13);
    
  }

}
