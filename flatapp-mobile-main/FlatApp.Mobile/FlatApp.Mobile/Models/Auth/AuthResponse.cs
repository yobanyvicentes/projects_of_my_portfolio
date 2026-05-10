namespace FlatApp.Mobile.Models.Auth;

public sealed record AuthResponse(string Token, AuthUser User);

public sealed record AuthMeResponse(AuthUser User);

public sealed record AuthUser(int Id, string Name, string Email);
