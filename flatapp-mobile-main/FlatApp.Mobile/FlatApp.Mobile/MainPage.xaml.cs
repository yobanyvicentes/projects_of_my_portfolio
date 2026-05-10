using Microsoft.AspNetCore.Components.WebView;
using Microsoft.Maui.ApplicationModel;

namespace FlatApp.Mobile
{
    public partial class MainPage : ContentPage
    {
        public MainPage()
        {
            InitializeComponent();
        }

        private void BlazorWebView_UrlLoading(object? sender, UrlLoadingEventArgs e)
        {
            if (e.Url.Scheme is not ("http" or "https"))
            {
                e.UrlLoadingStrategy = UrlLoadingStrategy.CancelLoad;
                _ = Launcher.Default.OpenAsync(e.Url);
                return;
            }

            if (IsBlazorWebViewInternalUrl(e.Url) || IsLocalDevelopmentUrl(e.Url))
            {
                e.UrlLoadingStrategy = UrlLoadingStrategy.OpenInWebView;
                return;
            }

            e.UrlLoadingStrategy = UrlLoadingStrategy.CancelLoad;
            _ = Launcher.Default.OpenAsync(e.Url);
        }

        private static bool IsBlazorWebViewInternalUrl(Uri url)
        {
            return url.Host is "0.0.0.0" or "0.0.0.1" or "localhost";
        }

        private static bool IsLocalDevelopmentUrl(Uri url)
        {
            return url.IsLoopback || url.Host.StartsWith("10.0.2.", StringComparison.OrdinalIgnoreCase);
        }
    }
}
