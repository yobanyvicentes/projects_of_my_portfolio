namespace FlatApp.Mobile.Models.Auth;

public sealed record RegisterRequest(string Name, string Email, string Password, string PasswordConfirmation, string DeviceName);
