namespace FlatApp.Mobile.Models.Auth;

public sealed record LoginRequest(string Email, string Password, string DeviceName);
