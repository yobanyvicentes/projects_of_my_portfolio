using System.Text.Json;

namespace FlatApp.Mobile.Services.Api;

public sealed class ApiException(int statusCode, string responseBody) : Exception($"API request failed with status code {statusCode}.")
{
    public int StatusCode { get; } = statusCode;

    public string ResponseBody { get; } = responseBody;

    public string ToUserMessage(string fallback = "The server rejected the request.")
    {
        if (string.IsNullOrWhiteSpace(ResponseBody))
        {
            return $"{fallback} Status code: {StatusCode}.";
        }

        try
        {
            using var document = JsonDocument.Parse(ResponseBody);
            var root = document.RootElement;

            if (root.TryGetProperty("message", out var messageElement) && messageElement.ValueKind == JsonValueKind.String)
            {
                var message = messageElement.GetString();

                if (!string.IsNullOrWhiteSpace(message))
                {
                    return message;
                }
            }

            if (root.TryGetProperty("errors", out var errorsElement) && errorsElement.ValueKind == JsonValueKind.Object)
            {
                foreach (var errorProperty in errorsElement.EnumerateObject())
                {
                    if (errorProperty.Value.ValueKind == JsonValueKind.Array)
                    {
                        foreach (var error in errorProperty.Value.EnumerateArray())
                        {
                            if (error.ValueKind == JsonValueKind.String)
                            {
                                var validationMessage = error.GetString();

                                if (!string.IsNullOrWhiteSpace(validationMessage))
                                {
                                    return validationMessage;
                                }
                            }
                        }
                    }
                }
            }
        }
        catch (JsonException)
        {
            return ResponseBody.Length > 220 ? ResponseBody[..220] : ResponseBody;
        }

        return $"{fallback} Status code: {StatusCode}.";
    }
}
