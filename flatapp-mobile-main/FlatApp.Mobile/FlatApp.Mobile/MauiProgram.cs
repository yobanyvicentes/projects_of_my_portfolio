using FlatApp.Mobile.Configuration;
using FlatApp.Mobile.Services.Activity;
using FlatApp.Mobile.Services.Api;
using FlatApp.Mobile.Services.Auth;
using FlatApp.Mobile.Services.Chores;
using FlatApp.Mobile.Services.Finance;
using FlatApp.Mobile.Services.Flats;
using FlatApp.Mobile.Services.Receipts;
using FlatApp.Mobile.Services.Shopping;
using Microsoft.Extensions.Logging;

namespace FlatApp.Mobile
{
    public static class MauiProgram
    {
        public static MauiApp CreateMauiApp()
        {
            var builder = MauiApp.CreateBuilder();
            builder
                .UseMauiApp<App>()
                .ConfigureFonts(fonts =>
                {
                    fonts.AddFont("OpenSans-Regular.ttf", "OpenSansRegular");
                });

            builder.Services.AddMauiBlazorWebView();

            var apiSettings = new ApiSettings();
            builder.Services.AddSingleton(apiSettings);
            builder.Services.AddSingleton<ITokenStore, SecureTokenStore>();
            builder.Services.AddTransient<ApiAuthorizationHandler>();

            builder.Services
                .AddHttpClient<IApiClient, ApiClient>(client =>
                {
                    client.BaseAddress = new Uri(apiSettings.BaseUrl.TrimEnd('/') + "/");
                    client.Timeout = apiSettings.Timeout;
                    client.DefaultRequestHeaders.Accept.ParseAdd("application/json");
                })
                .AddHttpMessageHandler<ApiAuthorizationHandler>();

            builder.Services
                .AddHttpClient<IReceiptFileService, ReceiptFileService>(client =>
                {
                    client.BaseAddress = new Uri(apiSettings.BaseUrl.TrimEnd('/') + "/");
                    client.Timeout = apiSettings.Timeout;
                    client.DefaultRequestHeaders.Accept.ParseAdd("application/octet-stream");
                })
                .AddHttpMessageHandler<ApiAuthorizationHandler>();

            builder.Services.AddScoped<IAuthService, AuthService>();
            builder.Services.AddScoped<IFlatService, FlatService>();
            builder.Services.AddScoped<IChoreService, ChoreService>();
            builder.Services.AddScoped<IShoppingService, ShoppingService>();
            builder.Services.AddScoped<IFinanceService, FinanceService>();
            builder.Services.AddScoped<IReceiptService, ReceiptService>();
            builder.Services.AddScoped<IActivityService, ActivityService>();

#if DEBUG
            builder.Services.AddBlazorWebViewDeveloperTools();
            builder.Logging.AddDebug();
#endif

            return builder.Build();
        }
    }
}
