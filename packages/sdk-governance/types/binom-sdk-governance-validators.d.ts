declare function validateEmail(value: string): boolean;

declare function validatePhone(value: string): boolean;

declare function validateIpv4(value: string): boolean;
declare function validateIpv6(value: string): boolean;

declare function luhnCheck(value: string): boolean;
declare function validateCreditCard(value: string): boolean;

declare function validateIban(value: string): boolean;

declare function validateGeoCoordinates(value: string): boolean;

export { luhnCheck, validateCreditCard, validateEmail, validateGeoCoordinates, validateIban, validateIpv4, validateIpv6, validatePhone };
