namespace FlatApp.Mobile.Configuration;

public sealed class ApiSettings
{
    public const string SectionName = "Api";

    public const string DefaultBaseUrl = "";

    public string BaseUrl { get; init; } = ResolveBaseUrl();

    public TimeSpan Timeout { get; init; } = TimeSpan.FromSeconds(30);

    private static string ResolveBaseUrl()
    {
        var overrideUrl = Environment.GetEnvironmentVariable("FLATNZ_API_URL");

        return string.IsNullOrWhiteSpace(overrideUrl)
            ? DefaultBaseUrl
            : overrideUrl.Trim();
    }
}
