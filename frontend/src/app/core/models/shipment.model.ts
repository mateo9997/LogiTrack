export interface Shipment {
  id?: number;
  carrierName: string;
  trackingNumber: string;
  estimatedDelivery?: string;
}
