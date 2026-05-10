using FlatApp.Mobile.Models.Shopping;
using FlatApp.Mobile.Services.Api;

namespace FlatApp.Mobile.Services.Shopping;

public sealed class ShoppingService(IApiClient apiClient) : IShoppingService
{
    public Task<ShoppingResponse?> GetAsync(CancellationToken cancellationToken = default)
    {
        return apiClient.GetAsync<ShoppingResponse>("shopping", cancellationToken);
    }

    public async Task<ShoppingItem?> CreateAsync(CreateShoppingItemRequest request, CancellationToken cancellationToken = default)
    {
        var response = await apiClient.PostAsync<CreateShoppingItemRequest, ShoppingItemResponse>("shopping", request, cancellationToken);

        return response?.Item;
    }

    public async Task<ShoppingItem?> UpdateAsync(int id, UpdateShoppingItemRequest request, CancellationToken cancellationToken = default)
    {
        var response = await apiClient.PutAsync<UpdateShoppingItemRequest, ShoppingItemResponse>($"shopping/{id}", request, cancellationToken);

        return response?.Item;
    }

    public async Task<ShoppingItem?> DeactivateAsync(int id, CancellationToken cancellationToken = default)
    {
        var response = await apiClient.PostAsync<object, ShoppingItemResponse>($"shopping/{id}/deactivate", new { }, cancellationToken);

        return response?.Item;
    }

    public async Task<ShoppingItem?> ReactivateAsync(int id, CancellationToken cancellationToken = default)
    {
        var response = await apiClient.PostAsync<object, ShoppingItemResponse>($"shopping/{id}/reactivate", new { }, cancellationToken);

        return response?.Item;
    }

    public Task<ShoppingPurchaseResponse?> PurchaseAsync(int id, PurchaseShoppingItemRequest request, CancellationToken cancellationToken = default)
    {
        return apiClient.PostAsync<PurchaseShoppingItemRequest, ShoppingPurchaseResponse>($"shopping/{id}/purchase", request, cancellationToken);
    }
}
